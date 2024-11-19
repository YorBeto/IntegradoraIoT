<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;


class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');


        $user = User::where('email', $credentials['email'])->first();

        if($user->email_verified_at == null){
            return response()->json(['error' => 'Cuenta no activada'], 401);
        }

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json(['error' => 'Credenciales inválidas'], 401);
        }

        // Generar el token
        $token = JWTAuth::claims(['email' => $user->email, 'id' => $user->id])->fromUser($user);

        return response ()->json(['token' => $token,
        'token_type' => 'bearer',
        'expires_in' => Auth::guard('api')->factory()->getTTL() * 60,]);
    }

}
