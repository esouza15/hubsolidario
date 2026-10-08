<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, true)) {
            $request->session()->regenerate();
            $user = Auth::user();

            $rotaDestino = match ($user->perfil ?? 'doador') {
                'agente_triagem' => 'triagem.index',
                'gestor' => 'instituicao.dashboard',
                default => 'doador.index',
            };

            return redirect()->route($rotaDestino)->with('success', 'Bem-vindo de volta! Seu acesso como ' . $user->perfil_label . ' foi liberado.');
        }

        return redirect()->back()->withErrors([
            'email' => 'As credenciais informadas não coincidem com nossos registros.',
        ])->withInput();
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'perfil' => ['nullable', 'string', 'in:doador,agente_triagem,gestor'],
        ]);

        $perfil = $request->perfil ?? 'doador';

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'perfil' => $perfil,
        ]);

        Auth::login($user);

        $rotaDestino = match ($perfil) {
            'agente_triagem' => 'triagem.index',
            'gestor' => 'instituicao.dashboard',
            default => 'doador.index',
        };

        return redirect()->route($rotaDestino)->with('success', 'Cadastro realizado como ' . $user->perfil_label . '! Acesso liberado.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('doador.index')->with('success', 'Você saiu da sua conta. Os campos foram congelados novamente.');
    }
}

