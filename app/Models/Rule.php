<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rule extends Model
{
    protected $fillable = [
        'disease_id',
        'symptom_id',
        'mb',
        'md',
    ];

    protected $casts = [
        'mb' => 'float',
        'md' => 'float',
        'cf' => 'float',
    ];

    public function disease()
    {
        return $this->belongsTo(Disease::class);
    }

    public function symptom()
    {
        return $this->belongsTo(Symptom::class);
    }
}
