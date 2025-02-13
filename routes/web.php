<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompradorController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\SolicitacaoDeCompraController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Aqui é onde você pode registrar as rotas para sua aplicação.
| Essas rotas são carregadas pelo RouteServiceProvider e fazem
| parte do grupo "web", que contém middleware de sessões.
|
*/

// Rotas Públicas
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.process');
});

// Rotas Protegidas
Route::middleware('auth')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.process');
    Route::get('/home', [AuthController::class, 'home'])->name('home');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::match(['get', 'post'], '/eventos', [EventoController::class, 'index'])->name('eventos.index');
});
