<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    protected $fillable = [
        'user_id',
        'expert_system_id',
        'symptoms_data',
        'results',
    ];

    protected $casts = [
        'symptoms_data' => 'array',
        'results' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function expertSystem()
    {
        return $this->belongsTo(ExpertSystem::class);
    }
}
