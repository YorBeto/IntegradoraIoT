<?php

namespace App\Http\Controllers;

use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Rol;
use App\Models\User;
use Illuminate\Support\Facades\Validator;


class TutorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function DarAlta(Request $request)
    {
        $validator=Validator::make($request->all(),[
            'nombre_kid' => 'required|string',
            'apellido_paterno_kid' => 'required|string',
            'edad_kid' => 'required|integer',
            'genero_kid' => 'required|in:Masculino,Femenino',
            'foto_perfil_kid' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Errores de validación',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            $user = Auth::user();
            
            if ($user->email_verified_at == null) {
                return response()->json(['error' => 'Cuenta no activada, no puedes dar de alta'], 401);
            }

            $id_persona = JWTAuth::parseToken()->getClaim('id_persona');
            if (!$id_persona) {
                return response()->json(['error' => 'No se pudo obtener el id_persona del token.'], 400);
            }



            DB::statement('CALL altaKidYtutor(?, ?, ?, ?, ?, ?)', [
                $request->nombre_kid,
                $request->apellido_paterno_kid,
                $request->edad_kid,
                $request->genero_kid,
                $request->foto_perfil_kid ?? null,  
                $id_persona
            ]);

            $rolUsuario = Rol::where('nombre', 'tutor')->first();
            if ($rolUsuario) {
                DB::table('roles_usuarios')
                    ->updateOrInsert(
                        ['usuario_id' => $user->id],
                        ['rol_id' => $rolUsuario->id, 'updated_at' => now()]
                    );
            }

            return response()->json(['message' => 'El niño y el tutor se han registrado exitosamente.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Ocurrió un error al registrar el niño y el tutor: ' . $e->getMessage()], 500);
        }
    }
}
