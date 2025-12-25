<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpertSystem extends Model
{
    protected $fillable = [
        'expert_id',
        'name',
        'field',
        'description',
        'target_user',
        'status',
    ];

    public function expert()
    {
        return $this->belongsTo(User::class, 'expert_id');
    }

    public function diseases()
    {
        return $this->hasMany(Disease::class);
    }

    public function symptoms()
    {
        return $this->hasMany(Symptom::class);
    }

    public function confidenceScales()
    {
        return $this->hasMany(ConfidenceScale::class)->orderBy('order');
    }

    public function consultations()
    {
        return $this->hasMany(Consultation::class);
    }
}
