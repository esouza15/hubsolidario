<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPerfil
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$perfisPermitidos
     */
    public function handle(Request $request, Closure $next, string ...$perfisPermitidos): Response
    {
        if (!Auth::check()) {
            return redirect()->route('doador.index')->with('error', 'Acesso restrito. Faça login para acessar o módulo solicitado.');
        }

        $user = Auth::user();
        $perfilUsuario = $user->perfil ?? 'doador';

        // O perfil 'gestor' possui acesso total a todos os módulos
        if ($perfilUsuario === 'gestor') {
            return $next($request);
        }

        // Verifica se o perfil do usuário está entre os permitidos para a rota
        if (in_array($perfilUsuario, $perfisPermitidos)) {
            return $next($request);
        }

        // Redirecionamento amigável de acordo com o perfil do usuário caso tente acessar área não autorizada
        $rotasPadrao = [
            'doador' => 'doador.index',
            'agente_triagem' => 'triagem.index',
            'gestor' => 'instituicao.dashboard',
        ];

        $rotaRedirecionamento = $rotasPadrao[$perfilUsuario] ?? 'doador.index';
        $nomePerfil = match ($perfilUsuario) {
            'doador' => 'Doador',
            'agente_triagem' => 'Agente de Triagem',
            'gestor' => 'Gestor',
            default => 'Usuário',
        };

        return redirect()->route($rotaRedirecionamento)->with('error', "Acesso negado! O perfil '{$nomePerfil}' não possui permissão para acessar o módulo solicitado.");
    }
}

