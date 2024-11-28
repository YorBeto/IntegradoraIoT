<?php

// app/Http/Controllers/LedController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class LedController extends Controller
{
    private $client;
    private $aioKey;

    public function __construct()
    {
        $this->client = new Client([
            'verify' => false, // Desactiva la verificación SSL
        ]);
        $this->aioKey = env('AIO_KEY'); // Añade esto en tu archivo .env
    }

    public function toggleLed($state)
    {
        $response = $this->client->request('POST', "https://io.adafruit.com/api/v2/EquipoIoT/feeds/led1/data", [
            'headers' => [
                'X-AIO-Key' => $this->aioKey,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'value' => $state,
            ],
        ]);

        return response()->json(json_decode($response->getBody()->getContents(), true));
    }
}
