<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use App\Models\User; // Asegúrate de tener el modelo User importado

class PersonasController extends Controller
{
    public function registro(Request $request)
    {
        // Validación de los datos del usuario
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

        // Si la validación falla, devolver errores
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Errores de validación',
                'errors' => $validator->errors()
            ], 400);
        }

        // Insertar los datos en la base de datos usando el procedimiento almacenado
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

        // Obtener el usuario recién registrado (asumimos que su email es único)
        $user = User::where('email', $request->email)->first();

        // Generar el enlace de activación con una firma única
        $activationLink = URL::temporarySignedRoute(
            'activation.route', // Nombre de la ruta que manejará la activación
            now()->addMinutes(60), // Tiempo de expiración del enlace
            ['user' => $user->id]
        );

        // Enviar el correo de activación
        Mail::to($user->email)->send(new \App\Mail\AccountActivationMail($user, $activationLink));

        // Retornar una respuesta de éxito
        return response()->json(['message' => 'Usuario registrado exitosamente, revisa tu correo para activar tu cuenta.'], 201);
    }
}
