<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Rest;
use App\Http\Controllers\AtteController;
use DateTime;

class AtteControllerTest extends TestCase
{
    /*
        onDateChange関数のテスト
    */
    use RefreshDatabase;

    public function test_onDateChange()
    {
        /* ユーザの登録 */
        User::factory()->create([
            'name'=>'aaa',
            'email'=>'aaa@bbb.com',
            'password'=>'test12345'
        ]);
        User::factory()->create([
            'name'=>'bbb',
            'email'=>'bbb@ccc.com',
            'password'=>'test12345'
        ]);
        $this->assertDatabaseHas('users', [
            'name'=>'aaa',
            'email'=>'aaa@bbb.com',
            'password'=>'test12345'
        ]);
        $this->assertDatabaseHas('users', [
            'name'=>'bbb',
            'email'=>'bbb@ccc.com',
            'password'=>'test12345'
        ]);

        /* Attendanceの登録 */
        $now = new DateTime();
        $users = User::all();
        foreach($users as $user) {
            Attendance::create([
                'user_id'=>$user->id,
                'start_time'=>$now,
                'end_time'=>null
            ]);
        }
        $this->assertDatabaseHas('attendances', [
            'user_id'=>1,
            'start_time'=>$now,
            'end_time'=>null
        ]);
        $this->assertDatabaseHas('attendances', [
            'user_id'=>2,
            'start_time'=>$now,
            'end_time'=>null
        ]);

        /* Restの登録 */
        Rest::create([
            'attendance_id'=>1,
            'start_time'=>$now,
            'end_time'=>null
        ]);
        $this->assertDatabaseHas('rests', [
            'attendance_id'=>1,
            'start_time'=>$now,
            'end_time'=>null
        ]);

        /* onDateChange関数の実行 */
        AtteController::onDateChange();

        /* 実行後の確認 */
        $start_time = new DateTime($now->format('Y-m-d'.' 00:00:00'));
        $yesterday = clone $now;
        $yesterday->modify('-1 days');
        $end_time = new DateTime($yesterday->format('Y-m-d').' 23:59:59');
        $this->assertDatabaseHas('attendances', [
            'user_id'=>1,
            'start_time'=>$now,
            'end_time'=>$end_time
        ]);
        $this->assertDatabaseHas('attendances', [
            'user_id'=>2,
            'start_time'=>$now,
            'end_time'=>$end_time
        ]);
        $this->assertDatabaseHas('attendances', [
            'user_id'=>1,
            'start_time'=>$start_time,
            'end_time'=>null
        ]);
        $this->assertDatabaseHas('attendances', [
            'user_id'=>2,
            'start_time'=>$start_time,
            'end_time'=>null
        ]);
        $this->assertDatabaseHas('rests', [
            'attendance_id'=>1,
            'start_time'=>$now,
            'end_time'=>$end_time
        ]);
        $this->assertDatabaseHas('rests', [
            'start_time'=>$start_time,
            'end_time'=>null
        ]);
    }
}
