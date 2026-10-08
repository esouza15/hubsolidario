<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DoadorController;
use App\Http\Controllers\InstituicaoController;
use App\Http\Controllers\TriagemController;
use App\Http\Middleware\CheckPerfil;
use Illuminate\Support\Facades\Route;

// Rotas de Autenticação (Login / Cadastro / Logout)
Route::get('/login', function () {
    return redirect()->route('doador.index')->with('error', 'Acesso restrito. Faça login para continuar.');
})->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Módulo Doador (Página Inicial Pública; Submissão restrita a Doador e Gestor)
Route::get('/', [DoadorController::class, 'index'])->name('doador.index');

Route::middleware(['auth', CheckPerfil::class.':doador,gestor'])->group(function () {
    Route::post('/doar', [DoadorController::class, 'store'])->name('doador.store');
    Route::post('/agendar/{id}', [DoadorController::class, 'agendar'])->name('doador.agendar');
});

// Módulo de Triagem (BLINDADO: Acesso restrito a Agentes de Triagem e Gestores autenticados)
Route::middleware(['auth', CheckPerfil::class.':agente_triagem,gestor'])->group(function () {
    Route::get('/triagem', [TriagemController::class, 'index'])->name('triagem.index');
    Route::post('/triagem', [TriagemController::class, 'store'])->name('triagem.store');
    Route::patch('/triagem/confirmar-chegada/{id}', [TriagemController::class, 'confirmarChegada'])->name('triagem.confirmarChegada');
    Route::post('/triagem/receber/{id}', [TriagemController::class, 'confirmarChegada'])->name('triagem.receber');
});

// Módulo de Gestão Administrativo (BLINDADO: Acesso restrito exclusivo ao Gestor autenticado)
Route::middleware(['auth', CheckPerfil::class.':gestor'])->group(function () {
    Route::get('/painel', [InstituicaoController::class, 'dashboard'])->name('instituicao.dashboard');
    Route::delete('/painel/despachar/{id}', [InstituicaoController::class, 'despachar'])->name('instituicao.despachar');
});