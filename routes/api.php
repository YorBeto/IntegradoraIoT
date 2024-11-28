<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonasController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TutorController;
use App\Http\Controllers\EstadisticasController;
use App\Http\Controllers\ActivationController;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/restablecer-contrasena',[PersonasController::class, 'restablecercontrasena']);

Route::post('/registro', [PersonasController::class, 'registro']);
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');

Route::post('/alta', [TutorController::class, 'DarAlta'])->middleware('auth:api');

// Ruta para obtener las estadísticas generales de los kids de un tutor logueado
Route::get('/stats', [EstadisticasController::class, 'DatosGenerales'])->middleware('auth:api');

Route::get('/image', [ActivationController::class, 'show']);

// routes/web.php
use App\Http\Controllers\LedController;

Route::get('/led/{state}', [LedController::class, 'toggleLed']);
