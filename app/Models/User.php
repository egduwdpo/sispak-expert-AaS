<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function expertSystems()
    {
        return $this->hasMany(ExpertSystem::class, 'expert_id');
    }

    public function consultations()
    {
        return $this->hasMany(Consultation::class);
    }

    public function isPakar()
    {
        return $this->role === 'pakar';
    }
}