<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class MedicalProcedureNW extends Model
{
    use HasFactory;

    protected $table = 'medical_procedures_nw';

    protected $fillable = [
        'date',
        'user_name',
        'injection_vaccine',
        'iv_medication',
        'urea_blood_test',
        'venepuncture',
        'iv_cannulation',
        'swab_cs_nose_oral',
        'dressing',
        'ecg_12_led',
        'urinary_catheterization',
        'ng_tube_insertion',
        'nebulization',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
