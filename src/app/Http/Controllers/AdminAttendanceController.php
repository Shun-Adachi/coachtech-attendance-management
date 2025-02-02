<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Carbon\Carbon;

class AdminAttendanceController extends Controller
{

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
                'name' => '山田 太郎',
                'status' => '承認待ち',
                'date' => '2023/06/01',
                'app_date' => '2023/06/02',
                'note' => '電車遅延のため',
            ],
            '02' => [
                'name' => '西 玲奈',
                'status' => '承認待ち',
                'date' => '2023/06/02',
                'app_date' => '2023/06/03',
                'note' => '電車遅延のため',
            ],
        ];

        return view('admin.attendance.index', compact('user',  'currentDate', 'attendanceData'));
    }


    // 勤怠詳細画面
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

        return view('admin.attendance.show', compact('user', 'attendanceData'));
    }

    // スタッフ一覧画面
    public function showStaffIndex(Request $request)
    {
        $user = Auth::user();
        $staffList = User::where('role_id', config('constants.ROLE_USER'))->get();

        return view('admin.staff.index', compact('user', 'staffList'));
    }


    // スタッフ別勤怠一覧画面
    public function showStaffAttendance(Request $request, $year = null, $month = null)
    {
        $user = Auth::user();
        $staff = User::where('id', $request->user_id)->first();

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

        return view('admin.staff.attendance.index', compact('user', 'staff', 'currentDate', 'attendanceData', 'prevMonth', 'nextMonth'));
    }

    // 申請一覧画面
    public function showRequests(Request $request)
    {
        $user = Auth::user();
        $staff = User::first();

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

        return view('admin.applications.index', compact('user', 'attendanceData'));
    }

    // 修正申請承認画面
    public function approve(Request $request)
    {
        $user = Auth::user();

        // 勤怠データ（仮データをサンプルとして用意）
        $attendanceData = [
            '01' => [
                'start' => '09:00',
                'end' => '18:00',
                'break_in' => '12:00',
                'break_out' => '13:00',
                'note' => '電車遅のため
                あ
                あ',
            ]
        ];

        return view('admin.applications.approve', compact('user', 'attendanceData'));
    }
}
