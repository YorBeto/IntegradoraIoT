<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Models\Partida;
use App\Models\Juego;
use App\Models\Kid;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;


class GameOneController extends Controller
{
    public function sendAdafruitCommand($command)
    {
        $client = new Client();
        $apiKey = config('services.adafruit.aio_key');


    try {
        $response = $client->request('POST', 'https://io.adafruit.com/api/v2/EquipoIoT/feeds/game-status/data', [
            'headers' => [
                'X-AIO-Key' => $apiKey,
                'Content-Type' => 'application/json',
            ],
            'json' => ['value' => $command],
        ]);

        return json_decode($response->getBody(), true);

        } catch (\Exception $e) {
            \Log::error('Error al enviar comando a Adafruit: ' . $e->getMessage());
            return response()->json(['error' => 'Error al enviar comando a Adafruit: ' . $e->getMessage()], 500);
        }

    }

    public function iniciar(Request $request)
{
    $validator = Validator::make($request->all(), [
        'nombre' => 'required|string', 
        'nombre_kid' => 'required|string', 
    ]);

    if ($validator->fails()) {
        return response()->json([
            'message' => 'Errores de validación',
            'errors' => $validator->errors(),
        ], 422);
    }

    $juego = Juego::where('nombre', $request->nombre)->first();
    if (!$juego) {
        return response()->json([
            'message' => 'El juego especificado no existe.',
        ], 404);
    }

    $kid = Kid::where('nombre', $request->nombre_kid)->first();
    if (!$kid) {
        return response()->json([
            'message' => 'El kid especificado no existe.',
        ], 404);
    }

    $partidaActiva = Partida::where('id_juego', $juego->id_juego)
        ->whereNull('hora_fin')
        ->first();

    if ($partidaActiva) {
        return response()->json([
            'message' => 'Ya hay una partida activa para este juego. Finalízala antes de iniciar una nueva.',
        ], 400);
    }

    $partida = new Partida();
    $partida->id_juego = $juego->id_juego;
    $partida->id_kid = $kid->id_kid;
    $partida->fecha = now();
    $partida->hora_inicio = now();
    $partida->hora_fin = null;

    if (!$partida->save()) {
        return response()->json(['message' => 'Error al iniciar la partida.'], 400);
    }

    $this->sendAdafruitCommand('1');

    return response()->json(['message' => 'Partida iniciada correctamente.'], 200);
}   

    public function obtenerresultados(Request $request)
    {
        $user=Auth::user();
        $partidas = Partida::where('id_kid', $request->id_kid)
            ->where('id_juego', $request->id_juego)
            ->get();

            
        $partida = Partida::where('id_kid', $request->id_kid)
            ->where('id_juego', $request->id_juego)
            ->latest('id_partida')
            ->first();

        if (!$partida) {
            return response()->json(['message' => 'No se encontraron resultados para la partida especificada.'], 404);
        }

        $partida->juego = Juego::find($partida->id_juego);
        $partida->kid = Kid::find($partida->id_kid);

        return response()->json($partida);

    }

}