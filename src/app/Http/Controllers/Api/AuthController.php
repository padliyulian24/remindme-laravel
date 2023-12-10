<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:100',
            'password' => 'required|string|max:100',
        ]);

        $credentials = $request->only('email', 'password');
        $login = auth()->attempt($credentials, false);

        if ($login) {
            $user = User::select('id','email','name')->where('email', $request->email)->first();
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                "ok" => true,
                'data' => (object) array(
                    'user' => $user,
                    'access_token' => $token,
                    'refresh_token' => ''
                )
            ], 200);
        }

        return response()->json([
            "ok" => false,
            "err" => "ERR_INVALID_CREDS",
            "msg" => "incorrect username or password"
        ], 401);
    }
}
