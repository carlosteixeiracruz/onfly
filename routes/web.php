<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebPainelController;
use App\Http\Controllers\WebUsersController;
use App\Http\Controllers\WebViagemController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('login');
});

Route::get('/painel', [WebPainelController::class, 'index'])->name('painel.index');

Route::prefix('viagem')->group(function () {
    Route::get('/pedido', [WebViagemController::class, 'pedidoViagem'])->name('viagem.index');
    Route::get('/list', [WebViagemController::class, 'listPedidoViagem'])->name('listpedidoviagem.index');
    Route::post('/edit', [WebViagemController::class, 'edit']);
    Route::post('/salvarpedido', [WebViagemController::class, 'store']);
    Route::get('/listadmin/{id}', [WebViagemController::class, 'listAdminViagem'])->name('listadminviagem.index'); 
});

Route::prefix('users')->group(function () {
    //Route::post('/login', [ApiUsersController::class, 'login']); 
    Route::get('/login', [WebUsersController::class, 'showLoginForm'])->name('login.form');
    Route::post('/login', [WebUsersController::class, 'login'])->name('login.process');
    Route::get('/cadastro', [WebUsersController::class, 'cadastro'])->name('cadastro.index');
    Route::get('/list', [WebUsersController::class, 'listUsers'])->name('listusers.index');
});

