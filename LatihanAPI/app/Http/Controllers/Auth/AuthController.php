<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        try {
            $validated = $request->safe()->all();

            if (!Auth::attempt($validated)) {
                return response()->json(
                    [
                        'message' => 'Email atau password salah',
                        'data' => null,
                    ],
                    401
                );
            }

            // Retrieve the authenticated user after successful attempt
            $user = Auth::user();

            // Create token - note the correct method name is createToken (camelCase)
            $token = $user->createToken('laravel API', ['*'])->plainTextToken;

            return response()->json(
                [
                    'message' => 'Login Berhasil',
                    'user' => $user,
                    'token' => $token,
                ],
                200
            );
        } catch (Exception $e) {
            return response()->json(
                [
                    'message' => $e->getMessage(),
                    'data' => null,
                ],
                500
            );
        }
    }

    public function register(RegisterRequest $request)
    {
        try {
            $validated = $request->safe()->all();

            $validated['password'] = Hash::make($validated['password']);

            $user = User::create($validated);

            if ($user) {
                return response()->json(
                    [
                        'message' => 'Register berhasil',
                        'data' => $user,
                    ],
                    201
                );
            }
        } catch (Exception $e) {
            return response()->json(
                [
                    'message' => $e->getMessage(),
                    'data' => null,
                ],
                500
            );
        }
    }

    public function logout(Request $request)
    {
        try {
            // Correct access to current access token and delete it
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'message' => 'Berhasil Logout',
                'data' => null
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'data' => null
            ], 500);
        }
    }
}
