<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\ClearanceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ClearanceRequestController extends Controller
{
    private function isAdmin(): bool
    {
        return session('admin_logged_in') === true && in_array(session('admin_role'), ['admin', 'official'], true);
    }

    private function forbidUnlessAdmin()
    {
        if (! $this->isAdmin()) {
            abort(403, 'Forbidden');
        }
    }

    public function index(Request $request)
    {
        $isAdmin = $this->isAdmin();

        $query = ClearanceRequest::query()->latest('id');

        if (! $isAdmin) {
            $ownerKey = trim(strtolower((string) $request->query('owner_key', '')));
            $email = trim(strtolower((string) $request->query('email', '')));

            if ($ownerKey === '' && $email === '') {
                return response()->json([
                    'message' => 'owner_key or email is required',
                ], 422);
            }

            $query->where(function ($builder) use ($ownerKey, $email) {
                if ($ownerKey !== '') {
                    $builder->whereRaw('LOWER(owner_key) = ?', [$ownerKey]);
                }

                if ($email !== '') {
                    $builder->orWhereRaw('LOWER(email) = ?', [$email])
                        ->orWhereRaw('LOWER(owner_email) = ?', [$email]);
                }
            });
        }

        $requests = $query->get()->map(fn (ClearanceRequest $item) => $this->transform($item))->values();

        return response()->json([
            'data' => $requests,
        ]);
    }

    public function update(Request $request, string $ref)
    {
        $this->forbidUnlessAdmin();

        $validated = $request->validate([
            'status' => ['required', 'string', 'in:pending,approved,rejected'],
        ]);

        $clearanceRequest = ClearanceRequest::where('ref', $ref)->firstOrFail();
        $clearanceRequest->status = $validated['status'];
        $clearanceRequest->save();

        return response()->json([
            'message' => 'Request updated successfully.',
            'request' => $this->transform($clearanceRequest->fresh()),
        ]);
    }

    public function destroy(string $ref)
    {
        $this->forbidUnlessAdmin();

        $clearanceRequest = ClearanceRequest::where('ref', $ref)->firstOrFail();
        $filePath = $clearanceRequest->id_file_path;

        if ($filePath && Storage::disk('local')->exists($filePath)) {
            Storage::disk('local')->delete($filePath);
        }

        $clearanceRequest->delete();

        return response()->json([
            'message' => 'Request deleted successfully.',
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ref' => ['nullable', 'string', 'max:100'],
            'owner_key' => ['required', 'string', 'max:255'],
            'owner_name' => ['nullable', 'string', 'max:255'],
            'owner_email' => ['nullable', 'email', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'address' => ['required', 'string', 'max:500'],
            'age' => ['nullable', 'string', 'max:20'],
            'contact' => ['nullable', 'string', 'max:50'],
            'purpose' => ['required', 'string', 'max:255'],
            'purpose_reason' => ['required', 'string', 'max:500'],
            'idfile' => ['required', 'image', 'max:10240'],
        ]);

        $ref = trim((string) ($validated['ref'] ?? ''));
        if ($ref === '') {
            $ref = 'BR' . now()->format('YmdHis') . Str::upper(Str::random(5));
        }

        $file = $request->file('idfile');
        $extension = strtolower((string) $file->getClientOriginalExtension() ?: $file->guessExtension() ?: 'jpg');
        $fileName = Str::slug($ref) . '-' . Str::random(12) . '.' . $extension;
        $filePath = $file->storeAs('clearance-ids', $fileName);

        $clearanceRequest = ClearanceRequest::create([
            'ref' => $ref,
            'owner_key' => $validated['owner_key'],
            'owner_name' => $validated['owner_name'] ?? null,
            'owner_email' => $validated['owner_email'] ?? null,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'address' => $validated['address'],
            'age' => $validated['age'] ?? null,
            'contact' => $validated['contact'] ?? null,
            'purpose' => $validated['purpose'],
            'purpose_reason' => $validated['purpose_reason'],
            'status' => 'pending',
            'date_requested' => now()->toDateString(),
            'valid_until' => now()->addYear()->toDateString(),
            'id_file_name' => $file->getClientOriginalName(),
            'id_file_path' => $filePath,
            'id_file_mime' => $file->getClientMimeType(),
            'pdf_saved' => false,
        ]);

        return response()->json([
            'message' => 'Application submitted successfully.',
            'request' => $this->transform($clearanceRequest),
        ], 201);
    }

    public function image(string $ref)
    {
        $requestRecord = ClearanceRequest::where('ref', $ref)->firstOrFail();
        $path = $requestRecord->id_file_path;

        if (! $path || ! Storage::disk('local')->exists($path)) {
            abort(404);
        }

        return response()->file(Storage::disk('local')->path($path));
    }

    private function transform(ClearanceRequest $item): array
    {
        return [
            'id' => $item->id,
            'ref' => $item->ref,
            'ownerKey' => $item->owner_key,
            'ownerName' => $item->owner_name,
            'ownerEmail' => $item->owner_email,
            'name' => $item->name,
            'email' => $item->email,
            'address' => $item->address,
            'age' => $item->age,
            'contact' => $item->contact,
            'purpose' => $item->purpose,
            'purposeReason' => $item->purpose_reason,
            'status' => $item->status,
            'dateRequested' => optional($item->date_requested)->toDateString(),
            'validUntil' => optional($item->valid_until)->toDateString(),
            'idFileName' => $item->id_file_name,
            'idFilePath' => $item->id_file_path,
            'idFileMime' => $item->id_file_mime,
            'idFileUrl' => route('clearance-requests.image', ['ref' => $item->ref]),
            'pdfSaved' => (bool) $item->pdf_saved,
        ];
    }
}
