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
                    ],401 );
            }

            $user = $request->user();
            $token = $user->create_token('laravel APi', ['*'])->plainTextToken;

            return response()->json(
                [
                    'Message' => 'Login Berhasil',
                    'user' => $user,
                    'token' => $token,
                ],
                200,
            );
        } catch (Exception $e) {
            return response()->json(
                [
                    'message' => $e->getMessage(),
                    'data' => null,
                ],
                500,
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
                    201,
                );
            }
        } catch (Exception $e) {
            return response()->json(
                [
                    'message' => $e->getMessage(),
                    'data' => null,
                ],
                500,
            );
        }
    }

    public function logout()
    {
        try {
        } catch (Exception $e) {
        }
    }
}
