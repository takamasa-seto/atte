<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTime;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'start_time',
        'end_time'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeUserSearch($query, $user_id)
    {
        if (!empty($user_id)) {
            $query->where('user_id', $user_id);
        }
    }

    public function scopeOngoingSearch($query)
    {
        $query->whereNull('end_time');
    }

    public function scopeDateSearch($query, $date)
    {
        if(!empty($date)) {
            $query->whereDate('start_time', $date);
        }
    }
    
    public function addAttendance($user_id, $start_time, $end_time=null)
    {
        if(!empty($user_id) and !empty($start_time)) {
            $tmp_attendance = array(
                'user_id' => $user_id,
                'start_time' => $start_time,
                'end_time' => $end_time
            );
            return Attendance::create($tmp_attendance);
        }
    }

}
