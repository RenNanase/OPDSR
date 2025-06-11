<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitingConsultantLogForeigner extends Model
{
    use HasFactory;

    protected $table = 'visiting_consultants_log_foreigners';

    protected $fillable = [
        'visiting_consultants_log_id',
        'country',
        'gender',
    ];

    /**
     * Get the log entry that owns the foreigner record.
     */
    public function visitingConsultantLog()
    {
        return $this->belongsTo(VisitingConsultantLog::class, 'visiting_consultants_log_id', 'id');
    }
}