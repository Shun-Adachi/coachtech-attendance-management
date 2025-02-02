<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status_id',
        'attendance_at',
        'requested_at',
        'clock_in',
        'clock_out',
        'note',
    ];

    public function BreakTime()
    {
        return $this->belongsTo(BreakTime::class);
    }
}
