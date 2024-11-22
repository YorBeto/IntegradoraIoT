<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;

class EstadisticasController extends Controller
{
    public function DatosGenerales(Request $request)
    {
        // Obtén el ID del tutor desde el token
        $tutor_id = $request->user()->id;

        // Llama al procedimiento almacenado
        $resultados = DB::select('CALL datos_generales(?)', [$tutor_id]);

        // Retorna los datos en formato JSON
        return response()->json([
            'success' => true,
            'data' => $resultados
        ]);
    }
}
