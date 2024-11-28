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
use Illuminate\Support\Facades\Storage;

class PersonasController extends Controller
{
    public function registro(Request $request)
    {

    $request->merge([
        'apellido_paterno' => $request->input('apellido'),
        'fecha_nacimiento' => $request->input('fechaNacimiento'),
    ]);

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

    public function subirFoto(Request $request)
    {
        try {
            $archivo = $request->file('archivo');
    
            $rutaCarpeta = '23170136/';
    
            
            $user = User::find($request->input('id')); 
            if (!$user) {
                return response()->json(['msg' => 'Jugador no encontrado'], 404);
            }
            $path = Storage::disk('s3')->put($rutaCarpeta, $archivo);
            $user->foto_perfil = $path; 
            $user->save();
    
            return response()->json(['path' => $path], 201);
        } catch (\Exception $e) {
            return response()->json(['msg' => 'Error al subir la imagen: ' . $e->getMessage()], 500);
        }
    }
    

    public function verFotoPerfil(Request $request)
{
    $id = $request->input('id');
    $user = User::find($id);

    if (!$user || !$user->foto_perfil) {
        return response()->json(['msg' => 'No hay foto de perfil disponible'], 404);
    }

    $rutaFoto = $user->foto_perfil;

    // Verificar si el archivo existe en S3
    if (!Storage::disk('s3')->exists($rutaFoto)) {
        return response()->json(['msg' => 'La foto de perfil no se encuentra disponible'], 404);
    }

    $urlFoto = Storage::disk('s3')->temporaryUrl($rutaFoto, now()->addMinutes(5));

    return response()->json(['url' => $urlFoto], 200);
}
public function verFoto($id)
{
    $user = User::findOrFail($id);

    if (!$user->foto_perfil) {
        return response()->json(['msg' => 'No hay foto de perfil disponible'], 404);
    }

    $rutaFoto = $user->foto_perfil;

    if (!Storage::disk('s3')->exists($rutaFoto)) {
        return response()->json(['msg' => 'La foto de perfil no se encuentra disponible'], 404);
    }

    $imagen = Storage::disk('s3')->get($rutaFoto);

    $extension = pathinfo($rutaFoto, PATHINFO_EXTENSION);

    switch (strtolower($extension)) {
        case 'jpg':
        case 'jpeg':
            $mimeType = 'image/jpeg';
            break;
        case 'png':
            $mimeType = 'image/png';
            break;
        case 'gif':
            $mimeType = 'image/gif';
            break;
        case 'bmp':
            $mimeType = 'image/bmp';
            break;
        case 'webp':
            $mimeType = 'image/webp';
            break;
        default:
            return response()->json(['msg' => 'Tipo de imagen no soportado'], 415); 

    return response($imagen, 200)
    ->header('Content-Type', $mimeType) 
    ->header('Content-Disposition', 'inline; filename="' . basename($rutaFoto) . '"');
    }
}

    public function editarFotoPerfil(Request $request, $id)
    {
        
        $user = User::find($id);
        
        if (!$user) {
            return response()->json(['msg' => 'Usuario no encontrado'], 404);
        }
        
        $archivo = $request->file('archivo');
        
        if ($user->foto_perfil) {
            Storage::disk('s3')->delete($user->foto_perfil);
        }

        $rutaCarpeta = '23170136/User'; // Ajusta la ruta según tus necesidades
        $path = Storage::disk('s3')->put($rutaCarpeta, $archivo);
        $user->foto_perfil = $path; 

        $user->save();

        return response()->json(['msg' => 'Imagen actualizada exitosamente'], 200);
    }
    
}

