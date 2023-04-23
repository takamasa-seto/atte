<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\Attendance;
use App\Models\Rest;
use DateTime;

class AtteController extends Controller
{

    /* ユーザの状態を取得する関数 */
    private function getUserState($user_id)
    {
        $ongoing_attendances = Attendance::UserSearch($user_id)->OngoingSearch()->get();
        if (0 == count($ongoing_attendances)) {
            return array(
                'state' => "off",
                'user_id' => $user_id,
                'attendance_id' => null,
                'rest_id' => null);
        }
        foreach( $ongoing_attendances as $attendance) {
            $ongoing_rests = Rest::AttendanceSearch($attendance->id)->OngoingSearch()->get();
            if (count($ongoing_rests) > 0) {
                return array(
                    'state' => "resting",
                    'user_id' => $user_id,
                    'attendance_id' => $attendance->id,
                    'rest_id' => $ongoing_rests->first()->id);
            }
        }
        return array(
            'state' => "working",
            'user_id' => $user_id,
            'attendance_id' => $ongoing_attendances->first()->id,
            'rest_id' => null);
    }

    /* 日付が異なるか判定する */
    private function isAnotherDay(DateTime $start, DateTime $end)
    {
        $start_day = new DateTime($start->format('Y-m-d'));
        $end_day = new DateTime($end->format('Y-m-d'));
        $diff_day = $start_day->diff($end_day)->days;
        if ($diff_day > 0) {
            return True;
        }
        else {
            return False;
        }
    }

    /* 日付またぎ対応処理 */
    private function checkDateChange($state, $now)
    {
        if('off' != $state['state']) {
            $attendance = Attendance::find($state['attendance_id']);
            $start_time = new DateTime($attendance->start_time);
            if ($this->isAnotherDay($start_time, $now)) {
                $end_time = new DateTime($start_time->format('Y-m-d').' 23:59:59');
                $this->endWork($state, $end_time);
                $state = $this->getUserState($state['user_id']);
            }
        }
        return $state;
    }

    /* 勤務開始処理 */
    private function startWork($state, $start_time)
    {
        if ('off' != $state['state']) return;
        Attendance::addAttendance($state['user_id'], $start_time);
    }

    /* 勤務終了処理 */
    private function endWork($state, $end_time)
    {
        if (('working' != $state['state']) and ('resting' != $state['state'])) return;
        Attendance::find($state['attendance_id'])->update(['end_time' => $end_time]);

        /* 休憩中だったら休憩も終了する */
        if ('resting' == $state['state']) {
            Rest::find($state['rest_id'])->update(['end_time' => $end_time]);
        }
    }

    /* 休憩開始　*/
    private function startRest($state, $start_time)
    {
        if ('working' != $state['state']) return;
        Rest::addRest($state['attendance_id'], $start_time);
    }

    /* 休憩終了処理 */
    private function endRest($state, $end_time)
    {
        if (('resting' != $state['state'])) return;
        Rest::find($state['rest_id'])->update(['end_time' => $end_time]);
    }

    /* 日付ごとの労働時間つきAttendanceテーブルの取得 */
    private function getTimeWorkedTable($date)
    {
        $rests = Rest::whereNotNull('end_time')
                 ->select('attendance_id')
                 ->selectRaw('sec_to_time(SUM(time_to_sec(TIMEDIFF(end_time, start_time)))) AS total_break_time')
                 ->groupBy('attendance_id');
        $attendances = Attendance::with('user')
                 ->DateSearch($date)
                 ->whereNotNull('end_time')
                 ->leftJoinSub($rests, 'rests', function($join){
                        $join->on('id', '=', 'rests.attendance_id');
                    })
                 ->selectRaw('user_id,
                              TIME(start_time) AS start_time,
                              TIME(end_time) AS end_time,
                              IFNULL(total_break_time, sec_to_time(0)) AS total_break_time, sec_to_time(time_to_sec(TIMEDIFF(end_time, start_time)) - IFNULL(time_to_sec(total_break_time), 0)) AS time_worked');
        return $attendances;
    }

    /* 打刻ページの表示 */
    public function create()
    {
        if(Auth::check())
        {
            $now = new DateTime();
            $state = $this->getUserState(Auth::user()->id);
            $state = $this->checkDateChange($state, $now);
            return view('stamp', ['user_state' => $this->getUserState(Auth::user()->id)['state']]);
        }
        else
        {
            return view('auth.login');
        }
    }

    /* 打刻処理 */
    public function store(Request $request)
    {
        if (Auth::check()) {
            $action = isset($request->change_state) ? $request->change_state : '';
            $now = new DateTime();
            $state = $this->getUserState(Auth::user()->id);
            $state = $this->checkDateChange($state, $now);
            switch ($action) {
                case 'start_work':
                    $this->startWork($state, $now);
                    break;
                case 'end_work':
                    $this->endWork($state, $now);
                    break;
                case 'start_rest':
                    $this->startRest($state, $now);
                    break;
                case 'end_rest':
                    $this->endRest($state, $now);
                    break;
            }
            return redirect('/');
        } else {
            return view('auth.login');
        }
    }

    /* 日付一覧表示 */
    public function show(Request $request)
    {
        if ($request->input('change_date') == 'prev') {
            $date = new DateTime(session('date'));
            $date->modify('-1 days');
            session(['has_next' => true]);
        }
        elseif ($request->input('change_date') == 'next') {
            $date = new DateTime(session('date'));
            $date->modify('+1 days');
            $now = new DateTime('now');
            if (!$this->isAnotherDay($date, $now)) {
                session(['has_next' => false]);
            }
        }
        else {
            if (!session()->has('date')) {
                $date = new DateTime('now');
                session(['has_next' => false]);
            }
            else {
                $date = new DateTime(session('date'));
            }
        }
        
        $attendances = $this->getTimeWorkedTable($date)->Paginate(5);
        session(['date' => $date->format('Y-m-d')]);
        
        return view('date', compact('attendances'));
    }
}
