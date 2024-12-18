<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


use App\Http\Controllers\ActivationController;

Route::get('activar-cuenta/{user}', [ActivationController::class, 'activarCuenta'])
     ->name('activation.route')
     ->middleware('signed'); 

     Route::get('/restablecer-contrasena/{user}', [ActivationController::class, 'mostrarFormulario'])
    ->name('reset.route')
    ->middleware('signed');

Route::post('/restablecer', [ActivationController::class, 'actualizarContraseña'])
    ->name('password.update');
