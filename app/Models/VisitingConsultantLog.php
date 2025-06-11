<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\VisitingConsultantLogForeigner; // Import the new model
use App\Models\VisitingConsultantPatient;

class VisitingConsultantLog extends Model
{
    use HasFactory;

    protected $table = 'visiting_consultants_logs';

    protected $fillable = [
        'no_suite',
        'consultant_name',
        'total_patients_count',
        'time_in',
        'time_out',
        'ref_details',
        'remarks',
        'date',
        'user_name',
    ];

    protected $casts = [
        'date' => 'date',
        'time_in' => 'datetime',
        'time_out' => 'datetime',
    ];

    /**
     * Get the user that owns the VisitingConsultantLog.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_name', 'name');
    }

    /**
     * Get the foreigner patients for the VisitingConsultantLog.
     */
    public function foreignerPatients()
    {
        return $this->hasMany(VisitingConsultantLogForeigner::class, 'visiting_consultants_log_id');
    }

    /**
     * Get the patient statistics for the VisitingConsultantLog.
     */
    public function patients()
    {
        return $this->hasMany(VisitingConsultantPatient::class, 'visiting_consultants_log_id');
    }
}


