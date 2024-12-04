<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Juego;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use app\Models\Partida;
use Illuminate\Support\Facades\Storage;



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
        $juegos = Juego::select('id_juego','nombre','descripcion','imagen')->get();
        return response()->json($juegos);
    }

    public function imagen(Request $request)
{
    try {
        $archivo = $request->file('archivo');

        if (!$archivo) {
            return response()->json(['msg' => 'No se ha recibido ningún archivo'], 400);
        }

        $rutaCarpeta = 'games-images/';

        $nombreImagen = uniqid() . '_' . $archivo->getClientOriginalName();
        
        $path = Storage::disk('s3')->putFileAs($rutaCarpeta, $archivo, $nombreImagen);

        $urlPublica = Storage::disk('s3')->url($rutaCarpeta . $nombreImagen);

        $urlPublica = str_replace('s3.amazonaws.com', 's3.' . env('AWS_DEFAULT_REGION', 'us-east-2') . '.amazonaws.com', $urlPublica);

        if (!$urlPublica) {
            return response()->json(['msg' => 'No se pudo generar la URL pública para la imagen'], 500);
        }

        \Log::info('URL pública generada: ' . $urlPublica);

        $juego = Juego::find($request->input('id_juego'));

        if (!$juego) {
            return response()->json(['msg' => 'Juego no encontrado'], 404);
        }

        $juego->imagen = $urlPublica;
        $juego->save();

        return response()->json(['path' => $urlPublica], 201);
    } catch (\Exception $e) {
        \Log::error('Error al subir la imagen: ' . $e->getMessage());
        return response()->json(['msg' => 'Error al subir la imagen: ' . $e->getMessage()], 500);
    }
}

    

    

public function mostrar()
{
    try {
        $juegos = Juego::select('id_juego', 'nombre', 'descripcion', 'imagen')
            ->get()
            ->map(function ($juego) {
                return [
                    'id_juego' => $juego->id_juego,
                    'nombre' => $juego->nombre,
                    'descripcion' => $juego->descripcion,
                    'imagen' => $juego->imagen ? url($juego->imagen) : null, ];
            });

        if ($juegos->isEmpty()) {
            return response()->json(['msg' => 'No hay juegos disponibles'], 404);
        }

        return response()->json(['juegos' => $juegos], 200, [], JSON_UNESCAPED_SLASHES);
    } catch (\Exception $e) {
        return response()->json(['msg' => 'Error al mostrar los juegos: ' . $e->getMessage()], 500);
    }
}



}
