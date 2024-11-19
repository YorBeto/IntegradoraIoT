<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\Persona; 

class PersonasController extends Controller
{
    public function registro(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email', 
            'password' => 'required|min:6',  
            'nombre' => 'required|string',
            'apellido_paterno' => 'required|string',
            'apellido_materno' => 'nullable|string',  
            'fecha_nacimiento' => 'required|date',
            'sexo' => 'required|in:Masculino,Femenino', 
            'telefono' => 'nullable|string|max:20',  
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Errores de validación',
                'errors' => $validator->errors()
            ], 400);
        }

        DB::statement('CALL insertar_datos(?, ?, ?, ?, ?, ?, ?, ?, ?)', [
            $request->email, 
            Hash::make($request->password), 
            $request->foto_perfil ?? null,  
            $request->nombre, 
            $request->apellido_paterno, 
            $request->apellido_materno ?? null,  
            $request->fecha_nacimiento, 
            $request->sexo, 
            $request->telefono ?? null  
        ]);

        $user = User::where('email', $request->email)->first();

        $persona = $user->persona; 

        if (!$persona) {
            return response()->json(['message' => 'No se encontró la persona asociada al usuario.'], 400);
        }

        $activationLink = URL::temporarySignedRoute(
            'activation.route', 
            now()->addMinutes(60),
            ['user' => $user->id]
        );

        Mail::to($user->email)->send(new \App\Mail\AccountActivationMail($persona, $activationLink));

        return response()->json(['message' => 'Usuario registrado exitosamente, revisa tu correo para activar tu cuenta.'], 201);
    }

    public function restablecercontrasena(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Errores de validación',
                'errors' => $validator->errors()
            ], 400);
        }
    
        $user = User::where('email', $request->email)->first();
    
        $resetLink = URL::temporarySignedRoute(
            'reset.route', 
            now()->addMinutes(60), 
            ['user' => $user->id]
        );
    
        Mail::to($user->email)->send(new \App\Mail\PasswordReset($user, $resetLink));
    
        return response()->json(['message' => 'Se ha enviado un enlace a tu correo para restablecer tu contraseña.'], 200);
    }
    
}
