<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Persona;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;


class AuthController extends Controller
{
    public function login(Request $request)
{
    $credentials = $request->only('email', 'password');

    // Buscar usuario por email
    $user = User::where('email', $credentials['email'])->first();

    // Validar que el usuario exista y su cuenta esté activada
    if (!$user || !Hash::check($credentials['password'], $user->password)) {
        return response()->json(['error' => 'Credenciales inválidas'], 401);
    }

    if ($user->email_verified_at == null) {
        return response()->json(['error' => 'Cuenta no activada'], 401);
    }

    $persona = $user->persona;
    $nombre = $persona ? $persona->nombre : null;
    $apellido = $persona ? $persona->apellido_paterno: null;

    $token = JWTAuth::claims([
        'email' => $user->email,
        'id' => $user->id,
        'nombre' => $nombre,
        'apellido' => $apellido,
    ])->fromUser($user);

    return response()->json([
        'token' => $token,
        'token_type' => 'bearer',
        'expires_in' => Auth::guard('api')->factory()->getTTL() * 60,
    ]);
}


    public function logout()
    {
        $token = JWTAuth::getToken();

        JWTAuth::invalidate($token);
    
        return response()->json(['message' => 'Sesión cerrada']);
    }

}
