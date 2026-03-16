<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    protected $table = 'pasien';
    
    protected $fillable = [
        'gender',
        'age',
        'hypertension',
        'heart_disease',
        'ever_married',
        'work_type',
        'residence_type',
        'avg_glucose_level',
        'bmi',
        'smoking_status',
        'stroke',
    ];

    protected $casts = [
        'age' => 'float',
        'hypertension' => 'integer',
        'heart_disease' => 'integer',
        'avg_glucose_level' => 'float',
        'bmi' => 'float',
        'stroke' => 'integer',
    ];
}
