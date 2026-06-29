<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Resident;
use Illuminate\Http\Request;

class ResidentController extends Controller
{
    private function normalizeNamePart($value): ?string
    {
        $value = preg_replace('/\s+/', ' ', trim((string) $value));

        return $value === '' ? null : $value;
    }

    private function buildNameData(array $data): array
    {
        $firstName = $this->normalizeNamePart($data['first_name'] ?? null);
        $middleName = $this->normalizeNamePart($data['middle_name'] ?? null);
        $lastName = $this->normalizeNamePart($data['last_name'] ?? null);

        if (($firstName === null || $lastName === null) && ! empty($data['fullname'])) {
            $parts = preg_split('/\s+/', $this->normalizeNamePart($data['fullname']) ?? '') ?: [];

            if (count($parts) === 1) {
                $firstName ??= $parts[0];
                $lastName ??= null;
            } elseif (count($parts) === 2) {
                $firstName ??= $parts[0];
                $lastName ??= $parts[1];
            } elseif (count($parts) > 2) {
                $firstName ??= array_shift($parts);
                $lastName ??= array_pop($parts);
                $middleName ??= trim(implode(' ', $parts)) ?: null;
            }
        }

        $fullName = trim(implode(' ', array_filter([$firstName, $middleName, $lastName], static fn ($value) => $value !== null && $value !== '')));

        return [
            'first_name' => $firstName,
            'middle_name' => $middleName,
            'last_name' => $lastName,
            'fullname' => $fullName === '' ? null : $fullName,
        ];
    }

    /**
     * Get all residents
     */
    public function index()
    {
        return response()->json(Resident::with('user')->get());
    }

    /**
     * Get the currently signed-in resident profile by email.
     */
    public function profile(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        $email = strtolower(trim((string) $validated['email']));

        $resident = Resident::query()
            ->whereRaw('LOWER(email) = ?', [$email])
            ->first();

        if (! $resident) {
            return response()->json([
                'message' => 'Resident profile not found.',
            ], 404);
        }

        return response()->json([
            'data' => [
                'id' => $resident->id,
                'fullname' => $resident->fullname,
                'first_name' => $resident->first_name,
                'middle_name' => $resident->middle_name,
                'last_name' => $resident->last_name,
                'email' => $resident->email,
                'contact' => $resident->contact,
                'age' => $resident->age,
                'address' => $resident->address,
            ],
        ]);
    }

    /**
     * Create a new resident
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // 'user_id' => 'required|exists:users,id|unique:residents',
            'first_name' => 'nullable|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'fullname' => 'nullable|string|max:255',
            'age' => 'required|integer|min:1',
            'contact' => 'nullable|string',
            'address' => 'required|string',
        ]);

        $nameData = $this->buildNameData($validated);

        if ($nameData['first_name'] === null || $nameData['last_name'] === null) {
            return response()->json([
                'message' => 'First name and last name are required.',
            ], 422);
        }

        $resident = Resident::create([
            'first_name' => $nameData['first_name'],
            'middle_name' => $nameData['middle_name'],
            'last_name' => $nameData['last_name'],
            'fullname' => $nameData['fullname'],
            'age' => $validated['age'],
            'contact' => $validated['contact'] ?? null,
            'address' => $validated['address'],
        ]);

        return response()->json($resident, 201);
    }

    /**
     * Get a specific resident
     */
    public function show(Resident $resident)
    {
        return response()->json($resident->load('user', 'certificates'));
    }

    /**
     * Update a resident
     */
    public function update(Request $request, Resident $resident)
    {
        $validated = $request->validate([
            'first_name' => 'nullable|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'fullname' => 'nullable|string|max:255',
            'age' => 'integer|min:1',
            'contact' => 'nullable|string',
            'address' => 'string',
        ]);

        $nameData = $this->buildNameData($validated + [
            'fullname' => $validated['fullname'] ?? $resident->fullname,
            'first_name' => $validated['first_name'] ?? $resident->first_name,
            'middle_name' => $validated['middle_name'] ?? $resident->middle_name,
            'last_name' => $validated['last_name'] ?? $resident->last_name,
        ]);

        $resident->update([
            'first_name' => $nameData['first_name'],
            'middle_name' => $nameData['middle_name'],
            'last_name' => $nameData['last_name'],
            'fullname' => $nameData['fullname'],
            'age' => $validated['age'] ?? $resident->age,
            'contact' => array_key_exists('contact', $validated) ? $validated['contact'] : $resident->contact,
            'address' => $validated['address'] ?? $resident->address,
        ]);

        return response()->json($resident);
    }

    /**
     * Delete a resident
     */
    public function destroy(Resident $resident)
    {
        $resident->delete();

        return response()->json([
            'message' => 'Resident deleted successfully',
        ]);
    }
}
