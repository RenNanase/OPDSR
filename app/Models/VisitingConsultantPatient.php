<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitingConsultantPatient extends Model
{
    use HasFactory;

    protected $table = 'visiting_consultants_patients';

    protected $fillable = [
        'visiting_consultants_log_id',
        'chinese_count',
        'malay_count',
        'india_count',
        'kdms_count',
        'others_count',
        'male_count',
        'female_count',
        'new_male_count_vs',
        'new_female_count_vs',
    ];

    /**
     * Get the log entry that owns the patient statistics.
     */
    public function visitingConsultantLog()
    {
        return $this->belongsTo(VisitingConsultantLog::class, 'visiting_consultants_log_id', 'id');
    }
}
