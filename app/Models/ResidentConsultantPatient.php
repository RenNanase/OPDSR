<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResidentConsultantPatient extends Model
{
    use HasFactory;

    protected $fillable = [
        'resident_consultant_log_id',
        'chinese_count',
        'malay_count',
        'india_count',
        'kdms_count',
        'others_count',
        'male_count',
        'female_count',
        'new_male_count',
        'new_female_count'
    ];

    public function residentConsultantLog()
    {
        return $this->belongsTo(ResidentConsultantLog::class);
    }
}
