<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\BarangayOfficer;

class AdminSessionCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (session('admin_logged_in') !== true || ! in_array(session('admin_role'), ['admin', 'official'], true)) {
            return response()->json([
                'message' => 'Unauthorized. Admin or official access required.'
            ], 403);
        }

        // Ensure the session admin_id corresponds to an existing officer record.
        $adminId = session('admin_id');
        if (! $adminId) {
            return response()->json([
                'message' => 'Unauthorized. Admin identity not found.'
            ], 403);
        }

        $officer = BarangayOfficer::find($adminId);
        if (! $officer) {
            return response()->json([
                'message' => 'Unauthorized. Admin account not found.'
            ], 403);
        }

        // Ensure role in session still matches the stored role
        $role = strtolower(trim((string) session('admin_role')));
        $storedRole = strtolower(trim((string) ($officer->role ?? '')));
        if ($role !== $storedRole && ! in_array($storedRole, ['admin','official'], true)) {
            return response()->json([
                'message' => 'Unauthorized. Role mismatch.'
            ], 403);
        }

        return $next($request);
    }
}
