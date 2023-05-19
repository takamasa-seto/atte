<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rest extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendance_id',
        'start_time',
        'end_time'
    ];

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }

    public function scopeAttendanceSearch($query, $attendance_id)
    {
        if (!empty($attendance_id)) {
            $query->where('attendance_id', $attendance_id);
        }
    }

    public function scopeOngoingSearch($query)
    {
        $query->whereNull('end_time');
    }

    public function addRest($attendance_id, $start_time, $end_time=null)
    {
        if(!empty($attendance_id) and !empty($start_time)) {
            $tmp_rest = array(
                'attendance_id' => $attendance_id,
                'start_time' => $start_time,
                'end_time' => $end_time
            );
            return Rest::create($tmp_rest);
        }
    }

}
