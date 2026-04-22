<?php

use App\Http\Controllers\loginController;
use Illuminate\Support\Facades\Route;

Route::post('createJugador', [loginController::class, 'crearJugador'])->name('createJugador');
Route::post('login', [loginController::class, 'login'])->name('login');
