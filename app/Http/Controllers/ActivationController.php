<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ActivationController extends Controller
{
    public function activarCuenta(Request $request, User $user)
    {
        if (!$request->hasValidSignature()) {
            return response()->json(['message' => 'El enlace ha expirado o no es válido.'], 400);
        }

        // Activar la cuenta del usuario
        $user->email_verified_at = now();
        $user->save();

        return response()->json(['message' => 'Cuenta activada con éxito.'], 200);
    }
}
