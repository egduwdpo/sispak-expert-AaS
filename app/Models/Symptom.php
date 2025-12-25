<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Symptom extends Model
{
    protected $fillable = [
        'expert_system_id',
        'name',
        'description',
    ];

    public function expertSystem()
    {
        return $this->belongsTo(ExpertSystem::class);
    }

    public function rules()
    {
        return $this->hasMany(Rule::class);
    }

    public function diseases()
    {
        return $this->belongsToMany(Disease::class, 'rules')
            ->withPivot('mb', 'md', 'cf');
    }
}
