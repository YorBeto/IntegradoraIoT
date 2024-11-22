<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\Kid;
use App\Models\EstadisticasGenerales;

class EstadisticasController extends Controller
{
    public function estadisticas(Request $request)
    {
        try {
            // Obtener el usuario autenticado a través del token JWT
            $usuario = JWTAuth::parseToken()->authenticate();

            // Validar si el usuario tiene un perfil de tutor
            $tutor = $usuario->persona->tutor ?? null;

            if (!$tutor) {
                return response()->json(['error' => 'El usuario no es un tutor'], 403);
            }

            // Obtener los IDs de los niños asociados al tutor
            $kids = Kid::where('id_tutor', $tutor->id_tutor)->pluck('id_kid');

            if ($kids->isEmpty()) {
                return response()->json(['mensaje' => 'No hay niños registrados para este tutor'], 200);
            }

            // Consultar las estadísticas generales de los niños
            $estadisticas = EstadisticasGenerales::whereIn('id_kid', $kids)
                ->with(['kid', 'juego']) // Relacionar las tablas `kid` y `juego`
                ->get();

            return response()->json(['estadisticas' => $estadisticas], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Token inválido o no proporcionado'], 401);
        }
    }
}
