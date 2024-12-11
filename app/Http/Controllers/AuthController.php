<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Persona;
use Tymon\JWTAuth\Facades\JWTAuth;



class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // Buscar usuario por email
        $user = User::where('email', $credentials['email'])->first();

        // Verificar si el usuario existe y la contraseña es correcta
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json(['error' => 'Credenciales inválidas'], 401);
        }
        
        // Verificar si el correo electrónico está verificado
        if ($user->email_verified_at == null) {
            return response()->json(['error' => 'Cuenta no activada'], 401);
        }

        $persona = $user->persona;
        $nombre = $persona ? $persona->nombre : null;
        $apellido = $persona ? $persona->apellido_paterno: null;
        $id_persona = $persona ? $persona->id : null;

        // Generar token JWT con claims adicionales
        $token = JWTAuth::claims([
            'email' => $user->email,
            'id' => $user->id,
            'nombre' => $nombre,
            'apellido' => $apellido,
            'id_persona' => $id_persona,
        ])->fromUser($user);

        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::guard('api')->factory()->getTTL() * 240,
        ]);
    }

    public function logout()
    {
        $token = JWTAuth::getToken();

        JWTAuth::invalidate($token);
    
        return response()->json(['message' => 'Sesión cerrada']);
    }
}
