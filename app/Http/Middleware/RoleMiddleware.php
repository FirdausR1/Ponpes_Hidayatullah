<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        $userRole = $user->role ?? 'admin';

        // Superadmin selalu memiliki akses ke semua menu
        if ($userRole === 'superadmin') {
            return $next($request);
        }

        if (!empty($roles) && !in_array($userRole, $roles)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses ditolak. Anda tidak memiliki izin untuk tindakan ini.',
                ], 403);
            }

            // Jika bendahara mencoba mengakses menu di luar haknya, arahkan kembali dengan notifikasi
            return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak. Akun Anda (Bendahara) hanya diizinkan mengelola Data Santri dan Manajemen Pembayaran.');
        }

        return $next($request);
    }
}
