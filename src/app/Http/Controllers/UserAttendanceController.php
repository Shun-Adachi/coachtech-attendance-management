<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use Carbon\Carbon;

class UserAttendanceController extends Controller
{
    // 勤怠入力画面
    public function create(Request $request)
    {
        $user = Auth::user();
        $attendance = Attendance::where('user_id', $user->id)->get();

        return view('attendance.register', compact('user', 'attendance'));
    }

    // 勤怠一覧画面
    public function index(Request $request, $year = null, $month = null)
    {
        $user = Auth::user();

        // Carbonのロケールを日本語に設定
        Carbon::setLocale('ja');
        $currentDate = Carbon::now();
        if ($year && $month) {
            $currentDate = Carbon::create($year, $month, 1);
        }

        // 勤怠データ（仮データをサンプルとして用意）
        $attendanceData = [
            '01' => [
                'start' => '09:00',
                'end' => '18:00',
                'break' => '01:00',
                'total' => '08:00',
            ],
            '02' => [
                'start' => '10:00',
                'end' => '19:00',
                'break' => '01:00',
                'total' => '08:00',
            ],
            // 休みの場合やデータがない場合も考慮
        ];


        $prevMonth = $currentDate->copy()->subMonth();
        $nextMonth = $currentDate->copy()->addMonth();

        return view('attendance.index', compact('user', 'currentDate', 'attendanceData', 'prevMonth', 'nextMonth'));
    }


    // 勤怠一覧画面
    public function showAttendance(Request $request)
    {
        $user = Auth::user();

        // 勤怠データ（仮データをサンプルとして用意）
        $attendanceData = [
            '01' => [
                'start' => '09:00',
                'end' => '18:00',
                'break_in' => '12:00',
                'break_out' => '13:00',
                'note' => '電車遅延のため',
            ]
        ];

        return view('attendance.show', compact('user', 'attendanceData'));
    }

    // 申請一覧画面
    public function showRequests(Request $request)
    {
        $user = Auth::user();

        // 勤怠データ（仮データをサンプルとして用意）
        $attendanceData = [
            '01' => [
                'status' => '承認待ち',
                'date' => '2023/06/01',
                'app_date' => '2023/06/02',
                'note' => '電車遅延のため',
            ],
            '02' => [
                'status' => '承認待ち',
                'date' => '2023/06/02',
                'app_date' => '2023/06/03',
                'note' => '電車遅延のため',
            ],
            // 休みの場合やデータがない場合も考慮
        ];

        return view('applications.index', compact('user', 'attendanceData'));
    }
}
