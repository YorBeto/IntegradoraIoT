<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonasController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ActivationController;
use App\Http\Controllers\TutorController;
use App\Http\Controllers\JuegosController;
use App\Http\Controllers\GameOneController;
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


Route::post('/restablecer',[PersonasController::class, 'restablecercontrasena']);
Route::post('/registro', [PersonasController::class, 'registro']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');


//rutas de juego
Route::get('/obtenerJuegos', [JuegosController::class, 'mostrar']);
Route::post('/iniciar', [GameOneController::class, 'iniciar'])->middleware('auth:api');
Route::post('/terminar', [JuegosController::class, 'terminar'])->middleware('auth:api');
Route::post('/imagen', [JuegosController::class, 'imagen']);
Route::get('/juego/{id_juego}',[JuegosController::class, 'mostrarfotojuego']);

//ruta de tutores
Route::get('/tutores', [TutorController::class, 'obtenerNiñosDelTutor'])->middleware('auth:api');
Route::get('/obtenerTutor', [TutorController::class, 'charly'])->middleware('auth:api');
Route::post('/alta', [TutorController::class, 'DarAlta'])->middleware('auth:api');

//ruta de usuarios
Route::post('/foto',[PersonasController::class, 'subirfoto']);
// Route::get('/perfil', [PersonasController::class, 'verFotoPerfil']);
// Route::get('/perfil/{id}', [PersonasController::class, 'verFoto']);
// Route::post('/perfil', [PersonasController::class, 'editarFotoPerfil']);
Route::post('/perfil', [PersonasController::class, 'perfil']);
Route::put('/perfil', [PersonasController::class, 'editarperfil']);


Route::get('/perfil',function(Request $request){
    return response()->json(['message' => 'Chinga tu madre y ven a discord'], 201);
});