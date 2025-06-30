<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
class AuthController extends Controller {
    public function register(Request $request) {
        $request->validate([
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);
        $user = User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => 'admin',
        ]);
        
        return response()->json(['message' => 'User registered successfully', 'user' => $user], 201);
    }
    public function login(Request $request) {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);
        $user = User::where('username', $request->username)->first();
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'username' => ['The provided credentials do not match our records.'],
            ]);
        }
        
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['message' => 'Logged in successfully', 'token' => $token, 'user' => $user]);
    }
    public function logout(Request $request) {
        // Sanctum akan menghapus token yang sedang digunakan
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }
    public function user(Request $request) {
        // Auth::user() akan bekerja berkat middleware Sanctum
        return response()->json($request->user());
    }
}