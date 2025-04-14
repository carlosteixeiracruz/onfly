<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiUsersController;
use App\Http\Controllers\ApiPainelController;
use App\Http\Controllers\ApiViagemController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('users')->group(function () {
     Route::post('/insert', [ApiUsersController::class, 'store'])->name('api.users.insert');
});



Route::get('/paises', [ApiViagemController::class, 'listPais']);