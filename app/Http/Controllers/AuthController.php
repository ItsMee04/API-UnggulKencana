<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password'  =>  'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'The provided credentials are incorrect']);
        }

        $user->tokens()->delete();
        $accessToken = $user->createToken('token', ['access-api'], Carbon::now()->addDays(1)); // 1 hour expiration
        $user->token = $accessToken->plainTextToken;

        unset($user->email_verified_at);
        unset($user->created_at);
        unset($user->updated_at);
        unset($user->deleted_at);

        return response()->json(['success' => true, 'message' => 'Login Success', 'data' => $user]);
    }

    public function me()
    {
        return response()->json(['success' => true, 'data' => Auth::user()]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Successfully logged out']);
    }
}
