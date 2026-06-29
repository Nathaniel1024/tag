<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Resident;
use App\Models\ResidentRegistrationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    private function normalizeNamePart($value): ?string
    {
        $value = preg_replace('/\s+/', ' ', trim((string) $value));

        return $value === '' ? null : $value;
    }

    private function splitFullName(?string $fullName): array
    {
        $fullName = $this->normalizeNamePart($fullName);

        if ($fullName === null) {
            return [
                'first_name' => null,
                'middle_name' => null,
                'last_name' => null,
                'fullname' => null,
            ];
        }

        $parts = preg_split('/\s+/', $fullName) ?: [];

        if (count($parts) === 1) {
            return [
                'first_name' => $parts[0],
                'middle_name' => null,
                'last_name' => null,
                'fullname' => $parts[0],
            ];
        }

        if (count($parts) === 2) {
            return [
                'first_name' => $parts[0],
                'middle_name' => null,
                'last_name' => $parts[1],
                'fullname' => trim($parts[0] . ' ' . $parts[1]),
            ];
        }

        $firstName = array_shift($parts);
        $lastName = array_pop($parts);
        $middleName = trim(implode(' ', $parts));

        return [
            'first_name' => $firstName,
            'middle_name' => $middleName === '' ? null : $middleName,
            'last_name' => $lastName,
            'fullname' => trim($firstName . ' ' . $middleName . ' ' . $lastName),
        ];
    }

    private function buildNameData(array $data): array
    {
        $firstName = $this->normalizeNamePart($data['first_name'] ?? null);
        $middleName = $this->normalizeNamePart($data['middle_name'] ?? null);
        $lastName = $this->normalizeNamePart($data['last_name'] ?? null);

        if (($firstName === null || $lastName === null) && ! empty($data['fullname'])) {
            $fallback = $this->splitFullName($data['fullname']);
            $firstName ??= $fallback['first_name'];
            $middleName ??= $fallback['middle_name'];
            $lastName ??= $fallback['last_name'];
        }

        $fullName = trim(implode(' ', array_filter([$firstName, $middleName, $lastName], static fn ($value) => $value !== null && $value !== '')));

        return [
            'first_name' => $firstName,
            'middle_name' => $middleName,
            'last_name' => $lastName,
            'fullname' => $fullName === '' ? null : $fullName,
        ];
    }

    private function userPayload(User $user): array
    {
        return [
            'id' => $user->id,
            'fullname' => $user->fullname,
            'name' => $user->fullname,
            'first_name' => $user->first_name,
            'middle_name' => $user->middle_name,
            'last_name' => $user->last_name,
            'username' => $user->username,
            'email' => $user->email,
            'age' => $user->age,
            'contact' => $user->contact,
            'address' => $user->address,
            'role' => $user->role,
        ];
    }

    private function residentPayload(Resident $resident): array
    {
        return [
            'id' => $resident->id,
            'fullname' => $resident->fullname,
            'name' => $resident->fullname,
            'first_name' => $resident->first_name,
            'middle_name' => $resident->middle_name,
            'last_name' => $resident->last_name,
            'email' => $resident->email,
            'contact' => $resident->contact,
            'age' => $resident->age,
            'address' => $resident->address,
        ];
    }

    private function extractProfileImageData(Request $request): array
    {
        $file = $request->file('profile_image');

        if (! $file) {
            return [
                'profile_image' => null,
                'profile_image_mime' => null,
            ];
        }

        $contents = @file_get_contents($file->getRealPath());

        return [
            'profile_image' => $contents === false ? null : $contents,
            'profile_image_mime' => $file->getMimeType() ?: $file->getClientMimeType() ?: null,
        ];
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
            'status' => $requestModel->status,
            'reviewed_by' => $requestModel->reviewed_by,
            'reviewed_at' => optional($requestModel->reviewed_at)->toDateTimeString(),
            'decision_reason' => $requestModel->decision_reason,
        ];
    }

    public function logout(Request $request)
    {
        $request->user()?->currentAccessToken()?->delete();

        return response()->json([
            'message' => 'Logged out successfully.',
        ]);
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|string',
        ]);

        $email = strtolower(trim((string) $validated['email']));

        $user = User::query()
            ->whereRaw('LOWER(email) = ?', [$email])
            ->first();

        if (! $user) {
            $registrationRequest = ResidentRegistrationRequest::query()
                ->whereRaw('LOWER(email) = ?', [$email])
                ->latest('id')
                ->first();

            if ($registrationRequest) {
                if ($registrationRequest->status === 'pending') {
                    return response()->json([
                        'message' => 'Your registration is still pending approval.',
                    ], 403);
                }

                if ($registrationRequest->status === 'declined') {
                    return response()->json([
                        'message' => 'Your registration was declined. Please contact the barangay office.',
                    ], 403);
                }

                $nameData = $this->buildNameData([
                    'first_name' => $registrationRequest->first_name ?? null,
                    'middle_name' => $registrationRequest->middle_name ?? null,
                    'last_name' => $registrationRequest->last_name ?? null,
                    'fullname' => $registrationRequest->fullname ?? null,
                ]);

                $user = User::create([
                    'name' => $nameData['fullname'] ?? $registrationRequest->fullname,
                    'username' => $registrationRequest->username ?: $email,
                    'first_name' => $nameData['first_name'],
                    'middle_name' => $nameData['middle_name'],
                    'last_name' => $nameData['last_name'],
                    'fullname' => $nameData['fullname'] ?? $registrationRequest->fullname,
                    'email' => $email,
                    'password' => $registrationRequest->password_hash,
                    'age' => $registrationRequest->age,
                    'contact' => $registrationRequest->contact,
                    'address' => $registrationRequest->address,
                    'profile_image' => $registrationRequest->profile_image,
                    'profile_image_mime' => $registrationRequest->profile_image_mime,
                    'role' => 'resident',
                ]);

                Resident::updateOrCreate(
                    ['email' => $registrationRequest->email],
                    [
                        'first_name' => $nameData['first_name'],
                        'middle_name' => $nameData['middle_name'],
                        'last_name' => $nameData['last_name'],
                        'fullname' => $nameData['fullname'] ?? $registrationRequest->fullname,
                        'contact' => $registrationRequest->contact,
                        'age' => $registrationRequest->age,
                        'address' => $registrationRequest->address,
                        'profile_image' => $registrationRequest->profile_image,
                        'profile_image_mime' => $registrationRequest->profile_image_mime,
                    ]
                );
            }
        }

        if (! $user) {
            $resident = Resident::query()
                ->whereRaw('LOWER(email) = ?', [$email])
                ->first();

            if ($resident) {
                $nameData = $this->buildNameData([
                    'first_name' => $resident->first_name ?? null,
                    'middle_name' => $resident->middle_name ?? null,
                    'last_name' => $resident->last_name ?? null,
                    'fullname' => $resident->fullname ?? null,
                ]);

                $user = User::create([
                    'name' => $nameData['fullname'] ?? $resident->fullname,
                    'username' => $email,
                    'first_name' => $nameData['first_name'],
                    'middle_name' => $nameData['middle_name'],
                    'last_name' => $nameData['last_name'],
                    'fullname' => $nameData['fullname'] ?? $resident->fullname,
                    'email' => $email,
                    'password' => Hash::make($validated['password']),
                    'age' => $resident->age,
                    'contact' => $resident->contact,
                    'address' => $resident->address,
                    'role' => 'resident',
                ]);
            }
        }

        if ($user && strtolower((string) ($user->role ?? '')) !== 'resident') {
            $user->role = 'resident';
            $user->save();
        }

        if ($user && Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'message' => 'Resident login successful',
                'user' => $this->userPayload($user),
            ]);
        }

        if ($user && hash_equals((string) $user->password, (string) $validated['password'])) {
            $user->password = Hash::make($validated['password']);
            $user->save();

            return response()->json([
                'message' => 'Resident login successful',
                'user' => $this->userPayload($user),
            ]);
        }

        if (! $user) {
            return response()->json([
                'message' => 'Invalid resident credentials.'
            ], 401);
        }

        return response()->json([
            'message' => 'Invalid resident credentials.'
        ], 401);
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'profile_image' => 'nullable|image|max:10240',
            'contact' => 'nullable|string|max:20',
            'age' => 'required|integer|min:1',
            'address' => 'required|string|max:255',
        ]);

        $nameData = $this->buildNameData($validated);
        $imageData = $this->extractProfileImageData($request);

        if ($nameData['first_name'] === null || $nameData['last_name'] === null) {
            return response()->json([
                'message' => 'First name and last name are required.',
            ], 422);
        }

        $pendingDuplicate = ResidentRegistrationRequest::query()
            ->where('status', 'pending')
            ->where(function ($query) use ($validated) {
                $query->whereRaw('LOWER(email) = ?', [strtolower((string) $validated['email'])])
                    ->orWhereRaw('LOWER(username) = ?', [strtolower((string) $validated['username'])]);
            })
            ->exists();

        if ($pendingDuplicate) {
            return response()->json([
                'message' => 'A registration request with this email or username is already pending review.',
            ], 422);
        }

        $registrationRequest = DB::transaction(function () use ($validated, $imageData, $nameData) {
            $nameData = $this->buildNameData($validated);

            return ResidentRegistrationRequest::create([
                'first_name' => $nameData['first_name'],
                'middle_name' => $nameData['middle_name'],
                'last_name' => $nameData['last_name'],
                'fullname' => $nameData['fullname'],
                'username' => $validated['username'],
                'email' => $validated['email'],
                'password_hash' => Hash::make($validated['password']),
                'contact' => $validated['contact'] ?? null,
                'age' => $validated['age'],
                'address' => $validated['address'],
                'profile_image' => $imageData['profile_image'],
                'profile_image_mime' => $imageData['profile_image_mime'],
                'status' => 'pending',
            ]);
        });

        return response()->json([
            'message' => 'Registration submitted for approval.',
            'request' => $this->requestPayload($registrationRequest),
        ], 201);
    }
}
