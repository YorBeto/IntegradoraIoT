<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Persona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Url;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ActivationController extends Controller
{
    public function activarCuenta(Request $request, User $user)
{
    if (!$request->hasValidSignature()) {
        return response()->json(['message' => 'El enlace ha expirado o no es válido.'], 400);
    }

    $user->email_verified_at = now();
    $user->save();

    $rolUsuario = \App\Models\Rol::where('nombre', 'usuario')->first();

    if ($rolUsuario) {
        \DB::table('roles_usuarios')
            ->updateOrInsert(
                ['usuario_id' => $user->id], 
                ['rol_id' => $rolUsuario->id, 'updated_at' => now()] 
            );
    }
    return view('activationSuccess');
}


    public function mostrarFormulario(Request $request, $userId)
{
    if (!$request->hasValidSignature()) {
        return response()->json(['message' => 'El enlace ha expirado o no es válido.'], 403);
    }

    return view('passwordInput', ['userId' => $userId]);
}

public function actualizarContraseña(Request $request)
{
    $request->validate([
        'userId' => 'required|exists:users,id',
        'password' => 'required|min:8|confirmed', 
    ]);

    $user = User::find($request->userId);
    $user->password = Hash::make($request->password);
    $user->save();

    return view('passwordResetSuccess');
}

    public function show()
    {
        $filename = 'GamyG1.png';
        $path = public_path('images/' . $filename);

        if (!File::exists($path)) {
            abort(404);
        }

        $file = File::get($path);
        $type = File::mimeType($path);

        $response = response($file, 200);
        $response->header("Content-Type", $type);

        return $response;
    }

    public function imagen(Request $request) {
    try {
        // Obtén el archivo de la solicitud
        $archivo = $request->file('archivo');

        $rutaCarpeta = '23170136/mails/';

        $path = Storage::disk('s3')->put($rutaCarpeta, $archivo);

        return response()->json(['path' => $path], 201);
        
    } catch (\Exception $e) {
        return response()->json(['msg' => 'Error al subir la imagen: ' . $e->getMessage()], 500);
    }
}

    
}
