<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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

        return view('activationSuccess');
    }

    public function Restablecimiento(Request $request, User $user){

        if(!$request ->hasValidSignature()){
            return response()->json(['message' => 'El enlace ha expirado o no es válido.'], 400);
        }

        $user->password=Hash::make($request->password);
        $user->save();

        return view('passwordResetSuccess');
    }

}
