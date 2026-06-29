<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Resident;
use App\Models\ResidentRegistrationRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ResidentRegistrationRequestController extends Controller
{
    private function adminAllowed(Request $request): bool
    {
        return session('admin_logged_in') === true
            && in_array(session('admin_role'), ['admin', 'official'], true);
    }

    private function requestPayload(ResidentRegistrationRequest $requestModel): array
    {
        return [
            'id' => $requestModel->id,
            'fullname' => $requestModel->fullname,
            'name' => $requestModel->fullname,
            'first_name' => $requestModel->first_name,
            'middle_name' => $requestModel->middle_name,
            'last_name' => $requestModel->last_name,
            'username' => $requestModel->username,
            'email' => $requestModel->email,
            'contact' => $requestModel->contact,
            'age' => $requestModel->age,
            'address' => $requestModel->address,
            'status' => $requestModel->status,
            'decision_reason' => $requestModel->decision_reason,
            'reviewed_by' => $requestModel->reviewed_by,
            'reviewed_at' => optional($requestModel->reviewed_at)->toDateTimeString(),
            'created_at' => optional($requestModel->created_at)->toDateTimeString(),
            'updated_at' => optional($requestModel->updated_at)->toDateTimeString(),
            'has_image' => ! empty($requestModel->profile_image),
            'image_url' => route('resident-registration-requests.image', ['id' => $requestModel->id]),
        ];
    }

    private function requestToUserData(ResidentRegistrationRequest $requestModel): array
    {
        return [
            'name' => $requestModel->fullname,
            'username' => $requestModel->username,
            'first_name' => $requestModel->first_name,
            'middle_name' => $requestModel->middle_name,
            'last_name' => $requestModel->last_name,
            'fullname' => $requestModel->fullname,
            'email' => $requestModel->email,
            'password' => $requestModel->password_hash,
            'age' => $requestModel->age,
            'contact' => $requestModel->contact,
            'address' => $requestModel->address,
            'profile_image' => $requestModel->profile_image,
            'profile_image_mime' => $requestModel->profile_image_mime,
            'role' => 'resident',
        ];
    }

    public function index(Request $request)
    {
        if (! $this->adminAllowed($request)) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $items = ResidentRegistrationRequest::query()
            ->orderByDesc('created_at')
            ->get()
            ->map(fn ($item) => $this->requestPayload($item))
            ->values();

        return response()->json(['data' => $items]);
    }

    public function show(Request $request, $id)
    {
        if (! $this->adminAllowed($request)) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $residentRegistrationRequest = ResidentRegistrationRequest::query()->findOrFail($id);

        return response()->json([
            'data' => $this->requestPayload($residentRegistrationRequest),
        ]);
    }

    public function image(Request $request, $id)
    {
        if (! $this->adminAllowed($request)) {
            abort(403);
        }

        $residentRegistrationRequest = ResidentRegistrationRequest::query()->findOrFail($id);

        if (! $residentRegistrationRequest->profile_image) {
            abort(404);
        }

        return response($residentRegistrationRequest->profile_image)
            ->header('Content-Type', $residentRegistrationRequest->profile_image_mime ?: 'application/octet-stream')
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
    }

    public function approve(Request $request, $id)
    {
        if (! $this->adminAllowed($request)) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $residentRegistrationRequest = ResidentRegistrationRequest::query()->findOrFail($id);

        $actor = (string) session('admin_name', 'Admin');

        $user = DB::transaction(function () use ($residentRegistrationRequest, $actor) {
            $user = User::updateOrCreate(
                ['email' => $residentRegistrationRequest->email],
                $this->requestToUserData($residentRegistrationRequest)
            );

            Resident::updateOrCreate(
                ['email' => $residentRegistrationRequest->email],
                [
                    'first_name' => $residentRegistrationRequest->first_name,
                    'middle_name' => $residentRegistrationRequest->middle_name,
                    'last_name' => $residentRegistrationRequest->last_name,
                    'fullname' => $residentRegistrationRequest->fullname,
                    'contact' => $residentRegistrationRequest->contact,
                    'age' => $residentRegistrationRequest->age,
                    'address' => $residentRegistrationRequest->address,
                    'profile_image' => $residentRegistrationRequest->profile_image,
                    'profile_image_mime' => $residentRegistrationRequest->profile_image_mime,
                ]
            );

            $residentRegistrationRequest->update([
                'status' => 'approved',
                'reviewed_by' => $actor,
                'reviewed_at' => now(),
                'decision_reason' => null,
            ]);

            return $user;
        });

        return response()->json([
            'message' => 'Resident registration approved.',
            'user' => [
                'id' => $user->id,
                'fullname' => $user->fullname,
                'email' => $user->email,
                'username' => $user->username,
            ],
        ]);
    }

    public function decline(Request $request, $id)
    {
        if (! $this->adminAllowed($request)) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $residentRegistrationRequest = ResidentRegistrationRequest::query()->findOrFail($id);

        $validated = $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        $residentRegistrationRequest->update([
            'status' => 'declined',
            'reviewed_by' => (string) session('admin_name', 'Admin'),
            'reviewed_at' => now(),
            'decision_reason' => $validated['reason'] ?? null,
        ]);

        return response()->json([
            'message' => 'Resident registration declined.',
        ]);
    }
}
