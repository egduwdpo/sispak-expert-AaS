<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Disease extends Model
{
    protected $fillable = [
        'expert_system_id',
        'name',
        'category',
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

    public function symptoms()
    {
        return $this->belongsToMany(Symptom::class, 'rules')
            ->withPivot('mb', 'md', 'cf');
    }
}
