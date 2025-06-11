<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\ResidentConsultantLogForeigner; // Import the new model
use App\Models\ResidentConsultantPatient;

class ResidentConsultantLog extends Model
{
    use HasFactory;

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
        'time_in' => 'datetime',
        'time_out' => 'datetime',
    ];

    /**
     * Get the user that owns the ResidentConsultantLog.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the foreigner patients for the ResidentConsultantLog.
     */
    public function foreignerPatients()
    {
        return $this->hasMany(ResidentConsultantLogForeigner::class);
    }

    public function patients()
    {
        return $this->hasMany(ResidentConsultantPatient::class);
    }
}


