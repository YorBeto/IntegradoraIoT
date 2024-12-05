<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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

    public function subirfoto(Request $request){
        $archivo=$request->file('archivo');
        if(!$archivo){
            return response()->json(['msg' => 'No se ha recibido ningún archivo'], 400);
        }

        $rutaCarpeta = 'Profile-images/';
        $nombreImagen=uniqid().'_'.$archivo->getClientOriginalName();
        $path=Storage::disk('s3')->putFileAs($rutaCarpeta, $archivo, $nombreImagen);
        $urlPublica=Storage::disk('s3')->url($rutaCarpeta.$nombreImagen);
        $urlPublica=str_replace('s3.amazonaws.com', 's3.'.env('AWS_DEFAULT_REGION', 'us-east-2').'.amazonaws.com', $urlPublica);

        if(!$urlPublica){
            return response()->json(['msg' => 'No se pudo generar la URL pública para la imagen'], 500);
        }

        $user=User::find($request->input('id'));

        if(!$user){
            return response()->json(['msg' => 'No se encontró el usuario'], 404);
        }

        $user->foto_perfil=$urlPublica;
        $user->save();

        return response()->json(['msg' => 'Foto de perfil subida correctamente', 'url' => $urlPublica], 201);
    }

    public function perfil(Request $request){
        $user=User::find($request->input('id'));

        $usuario=DB::table('users')
        ->join('personas', 'users.id', '=', 'personas.usuario_id')
        ->where('users.id', $user->id)
        ->select('users.email', 'personas.nombre', 'personas.apellido_paterno', 'personas.apellido_materno', 'personas.fecha_nacimiento', 'personas.sexo', 'personas.telefono', 'users.foto_perfil')
        ->first();

        return response()->json(['usuario' => $usuario], 200);
        
    }

    

}

