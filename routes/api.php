<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonasController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ActivationController;
use App\Http\Controllers\TutorController;
use App\Http\Controllers\JuegosController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/restablecer-contrasena',[PersonasController::class, 'restablecercontrasena']);


Route::post('/registro', [PersonasController::class, 'registro']);
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');

Route::post('/alta', [TutorController::class, 'DarAlta'])->middleware('auth:api');


//rutas de juego
Route::get('/juegos', [JuegosController::class, 'ObtenerJuego']);
Route::post('/iniciar', [JuegosController::class, 'iniciar'])->middleware('auth:api');
