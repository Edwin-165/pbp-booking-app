<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Pastikan user sudah login
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthorized.'], 401);
        }

        // Cek apakah user memiliki role 'admin'
        // Asumsi kamu punya kolom 'role' di tabel 'users'
        // Atau kamu punya relasi/method untuk mengecek role
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Forbidden. You do not have admin access.'], 403);
        }

        return $next($request);
    }
}