<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    public function register(RegisterRequest $request)
{
   
    $validatedData = $request->validated();

    $user = User::create([
        'first_name' => $validatedData['first_name'],
        'last_name' => $validatedData['last_name'],
        'email' => $validatedData['email'],
        'password' => Hash::make($validatedData['password'])
    ]);
    $token = $user->createToken($user->name . '-AuthToken')->plainTextToken;

    return response()->json([
        'message' => "Registration successful",
        'user' => $user,
        'token' => $token
    ]);
}

public function login(LoginRequest $request)
{
    $validatedData = $request->validated();

    $credentials = [
        'email' => $validatedData['email'],
        'password' => $validatedData['password']
    ];

    $user = User::where('email', $credentials['email'])->first();

    if (!$user || !Hash::check($credentials['password'], $user->password)) {
        return response()->json([
            'message' => 'Invalid credentials'
        ], 401);
    }

    $token = $user->createToken($user->name . '-AuthToken')->plainTextToken;
    return response()->json([
        'user' => $user,
        'token' => $token
    ]);
}

    public function logout(Request $request) {
        if (Auth::user()) { 
            $request->user()->tokens()->delete();
        }

        return response()->json(['message' => 'You are logged out'], 200);
    }

    public function activeUser() {
        $activeUser = Auth::user();
        return response()->json($activeUser);
    }

    public function refresh()
    {
        $user = Auth::user();

    // Poništi sve pristupne tokene korisnika
    $user->tokens->each(function ($token, $key) {
        $token->delete();
    });

    // Pokreni Sanctum's TokenController kako bi stvorili novi token
    Artisan::call('token:create', ['--user_id' => $user->id, '--abilities' => '']);

    // Dobij novi token korisnika
    $newToken = User::find($user->id)->tokens->first();

    return response()->json([
        'status' => 'success',
        'user' => $user,
        'authorization' => [
            'token' => $newToken->plainTextToken,
            'expires_in' => now()->addWeeks(1)->timestamp, // Postavite vreme isteka po vašim potrebama
        ],
    ]);
    }
}
