<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResidentConsultantLogForeigner extends Model
{
    use HasFactory;

    protected $fillable = [
        'resident_consultant_log_id',
        'country',
        'gender',
    ];

    /**
     * Get the log entry that owns the foreigner record.
     */
    public function residentConsultantLog()
    {
        return $this->belongsTo(ResidentConsultantLog::class);
    }
}