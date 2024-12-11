<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Juego;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Partida;
use Illuminate\Support\Facades\Storage;

class JuegosController extends Controller
{
    public function ObtenerJuego()
    {
        $juegos = Juego::select('nombre', 'descripcion','imagen')->get();
        return response()->json($juegos);
    }

    public function terminar()
    {
        try {
            // Obtener la última partida registrada
            $partida = DB::table('partidas')->latest('id_partida')->first();
    
            if ($partida && !$partida->hora_fin) {
                // Calcular la duración de la partida
                $hora_inicio = new \DateTime($partida->hora_inicio);
                $hora_fin = new \DateTime();
                $intervalo = $hora_inicio->diff($hora_fin);
                $duracion = $intervalo->format('%H:%I:%S');
    
                // Actualizar la hora_fin de la partida
                DB::table('partidas')
                    ->where('id_partida', $partida->id_partida)
                    ->update(['hora_fin' => now()]);
    
                // Actualizar estadisticas_generales
                $estadisticas = DB::table('estadisticas_generales')
                    ->where('id_kid', $partida->id_kid)
                    ->where('id_juego', $partida->id_juego)
                    ->first();
    
                if ($estadisticas) {
                    $total_tiempo_jugado = new \DateTime($estadisticas->total_tiempo_jugado);
                    $total_tiempo_jugado->add(new \DateInterval('PT' . $intervalo->h . 'H' . $intervalo->i . 'M' . $intervalo->s . 'S'));
    
                    DB::table('estadisticas_generales')
                        ->where('id_kid', $partida->id_kid)
                        ->where('id_juego', $partida->id_juego)
                        ->update([
                            'numero_partidas' => $estadisticas->numero_partidas + 1,
                            'total_tiempo_jugado' => $total_tiempo_jugado->format('H:i:s'),
                            'updated_at' => now()
                        ]);
                }
            }
    
            return response()->json(['message' => 'Partida finalizada correctamente.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Ocurrió un error al finalizar la partida: ' . $e->getMessage()], 500);
        }
    }    

    public function Juegos()
    {
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
                        'imagen' => $juego->imagen ? url($juego->imagen) : null,
                    ];
                });

            if ($juegos->isEmpty()) {
                return response()->json(['msg' => 'No hay juegos disponibles'], 404);
            }

            return response()->json(['juegos' => $juegos], 200, [], JSON_UNESCAPED_SLASHES);
        } catch (\Exception $e) {
            return response()->json(['msg' => 'Error al mostrar los juegos: ' . $e->getMessage()], 500);
        }
    }

    public function obtenerEstadisticas(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre_kid' => 'required|string|max:50',
            'apellido_paterno_kid' => 'required|string|max:50'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Errores de validación',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            $nombre_kid = $request->input('nombre_kid');
            $apellido_paterno_kid = $request->input('apellido_paterno_kid');

            $estadisticas = DB::select('CALL obtenerEstadisticasKid(?, ?)', [$nombre_kid, $apellido_paterno_kid]);

            return response()->json(['estadisticas' => $estadisticas], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Ocurrió un error al obtener las estadísticas: ' . $e->getMessage()], 500);
        }
    }
}
