<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Resident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
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
            $resident = Resident::query()
                ->whereRaw('LOWER(email) = ?', [$email])
                ->first();

            if ($resident) {
                $user = User::create([
                    'name' => $resident->fullname,
                    'username' => $email,
                    'fullname' => $resident->fullname,
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
                'user' => [
                    'id' => $user->id,
                    'fullname' => $user->fullname,
                    'username' => $user->username,
                    'email' => $user->email,
                    'role' => $user->role,
                ],
            ]);
        }

        if ($user && hash_equals((string) $user->password, (string) $validated['password'])) {
            $user->password = Hash::make($validated['password']);
            $user->save();

            return response()->json([
                'message' => 'Resident login successful',
                'user' => [
                    'id' => $user->id,
                    'fullname' => $user->fullname,
                    'username' => $user->username,
                    'email' => $user->email,
                    'role' => $user->role,
                ],
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
            'fullname' => 'required|string|max:255',
            'contact' => 'nullable|string|max:20',
            'age' => 'required|integer|min:1',
            'address' => 'required|string|max:255',
        ]);

        $resident = DB::transaction(function () use ($validated) {
            User::create([
                'name' => $validated['fullname'],
                'username' => $validated['username'],
                'fullname' => $validated['fullname'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'age' => $validated['age'],
                'contact' => $validated['contact'] ?? null,
                'address' => $validated['address'],
                'role' => 'resident',
            ]);

            return Resident::create([
                'fullname' => $validated['fullname'],
                'email' => $validated['email'],
                'contact' => $validated['contact'] ?? null,
                'age' => $validated['age'],
                'address' => $validated['address'],
            ]);
        });

        return response()->json([
            'message' => 'Registered successfully',
            'resident' => $resident,
        ], 201);
    }
}
