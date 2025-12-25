<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfidenceScale extends Model
{
    protected $fillable = [
        'expert_system_id',
        'label',
        'value',
        'order',
    ];

    protected $casts = [
        'value' => 'float',
    ];

    public function expertSystem()
    {
        return $this->belongsTo(ExpertSystem::class);
    }
}
