<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Juego;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use app\Models\Partida;


class JuegosController extends Controller
{
    public function ObtenerJuego(){
        $juegos = Juego::select('nombre', 'descripcion','imagen')->get();
        return response()->json($juegos);
    }

    public function iniciar( Request $request){

        $validator = Validator::make($request->all(), [
            'id_juego' => 'required|exists:juegos,id',
            'id_kid' => 'required|exists:kids,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Errores de validación',
                'errors' => $validator->errors()
            ], 400);
        }

        $partida = new Partida();
        $partida->juego_id = $request->juego_id;
        $partida->id_kid = $request->id_kid;
        $partida->fecha = now();
        $partida->hora_inicio = now();
        $partida->hora_fin = null;
        $partida->save();

        return response()->json(['message' => 'Partida iniciada correctamente.'], 200);

    }

    public function Juegos(){
        $juegos = Juego::select('id_juego','nombre')->get();
        return response()->json($juegos);
    }

    

}
