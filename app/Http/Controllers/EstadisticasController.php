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
        $tutor_id = $request->user()->id;

        $resultados = DB::select('CALL datos_generales(?)', [$tutor_id]);

        return response()->json([
            'success' => true,
            'data' => $resultados
        ]);
    }
}
