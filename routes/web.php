<?php

use App\Http\Controllers\DoadorController;
use App\Http\Controllers\InstituicaoController;
use App\Http\Controllers\TriagemController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('triagem.index');
});

// Rota do doador (match direto)
Route::get('/', [DoadorController::class, 'index'])->name('doador.index');
Route::post('/doar', [DoadorController::class, 'store'])->name('doador.store');

// Rota de triagem (módulo do voluntário)
Route::get('/triagem', [TriagemController::class, 'index'])->name('triagem.index');
Route::post('/triagem', [TriagemController::class, 'store'])->name('triagem.store');

// Rota de demandas e estoque (módulo de gestão da instituição)
Route::get('/painel', [InstituicaoController::class, 'dashboard'])->name('instituicao.dashboard');
Route::delete('/painel/despachar/{id}', [InstituicaoController::class, 'despachar'])->name('instituicao.despachar');