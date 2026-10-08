<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'perfil',
    ];

    /**
     * Métodos de Verificação de Perfil
     */
    public function isDoador(): bool
    {
        return ($this->perfil ?? 'doador') === 'doador';
    }

    public function isAgenteTriagem(): bool
    {
        return ($this->perfil ?? 'doador') === 'agente_triagem';
    }

    public function isGestor(): bool
    {
        return ($this->perfil ?? 'doador') === 'gestor';
    }

    public function getPerfilLabelAttribute(): string
    {
        return match ($this->perfil ?? 'doador') {
            'gestor' => 'Gestor',
            'agente_triagem' => 'Triagem',
            default => 'Doador',
        };
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
