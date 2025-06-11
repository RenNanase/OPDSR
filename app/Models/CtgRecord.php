<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CtgRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'dr_geetha_count',
        'dr_joseph_count',
        'dr_sutha_count',
        'dr_ramesh_count',
        'user_name'
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
