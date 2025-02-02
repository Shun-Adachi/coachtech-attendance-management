<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use App\Models\BreakTime;
use Carbon\Carbon;

class UserAttendanceController extends Controller
{
    // 勤怠登録画面
    public function create(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today();
        $attendance = Attendance::where('user_id', $user->id)->whereDate('attendance_at', $today)->first();
        // 今日の勤怠が存在しない場合、新規作成
        if (!$attendance) {
            $attendance = Attendance::create([
                'user_id' => $user->id,
                'attendance_at' => $today,
                'status_id' => config('constants.STATUS_ATTENDANCE'),
            ]);
        }
        return view('attendance.register', compact('user', 'attendance'));
    }

    // 勤怠登録処理
    public function updateAttendance(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today();
        $attendance = Attendance::where('user_id', $user->id)->whereDate('attendance_at', $today)->first();

        // 勤務前の場合、勤務中ステータスに変更
        if ($attendance->status_id === config('constants.STATUS_ATTENDANCE')) {
            Attendance::where('id', $attendance->id)->update([
                'status_id' => config('constants.STATUS_WORKING'),
                'clock_in' => now(),
            ]);
            $attendance = Attendance::where('user_id', $user->id)->whereDate('attendance_at', $today)->first();
            $request->session()->flash('message', '勤務開始時刻を登録しました');
        }
        // 勤務中の場合、退勤ステータスに変更
        elseif ($attendance->status_id === config('constants.STATUS_WORKING')) {
            Attendance::where('id', $attendance->id)->update([
                'status_id' => config('constants.STATUS_LEAVING'),
                'clock_out' => now(),
            ]);
            $attendance = Attendance::where('user_id', $user->id)->whereDate('attendance_at', $today)->first();
            $request->session()->flash('message', '勤務を終了しました');
        }
        return redirect('/attendance');
    }

    // 休憩処理
    public function storeBreak(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today();
        $attendance = Attendance::where('user_id', $user->id)->whereDate('attendance_at', $today)->first();

        // 勤務中の場合、休憩入り処理を行う
        if ($attendance->status_id === config('constants.STATUS_WORKING')) {
            Attendance::where('id', $attendance->id)->update([
                'status_id' => config('constants.STATUS_BREAK'),
            ]);
            BreakTime::create([
                'attendance_id' => $attendance->id,
                'break_in' => now(),
            ]);
            $request->session()->flash('message', '休憩を開始しました');
        }
        // 休憩中の場合、休憩戻り処理を行う
        else if($attendance->status_id === config('constants.STATUS_BREAK')){
            Attendance::where('id', $attendance->id)->update([
                'status_id' => config('constants.STATUS_WORKING'),
            ]);
            BreakTime::where('attendance_id', $attendance->id)->whereNull('break_out')->first()->update([
                'attendance_id' => $attendance->id,
                'break_out' => now(),
            ]);
            $request->session()->flash('message', '休憩を終了しました');}

        return redirect('/attendance');
    }

    // 勤怠一覧画面
    public function index(Request $request, $year = null, $month = null)
    {
        $user = Auth::user();
        Carbon::setLocale('ja');
        $currentDate = $year && $month ? Carbon::create($year, $month, 1) : Carbon::now();
        $prevMonth = $currentDate->copy()->subMonth();
        $nextMonth = $currentDate->copy()->addMonth();
        $startOfMonth = $currentDate->copy()->startOfMonth();
        $endOfMonth = $currentDate->copy()->endOfMonth();

        // 今月の勤怠データを取得
        $attendances = Attendance::where('user_id', $user->id)
            ->whereBetween('attendance_at', [$startOfMonth, $endOfMonth])
            ->orderBy('attendance_at', 'asc') // 日付順にソート
            ->get();

        // 今月のカレンダーの日付リストを作成
        $daysInMonth = [];
        for ($date = $startOfMonth->copy(); $date->lte($endOfMonth); $date->addDay()) {
            $formattedDate = $date->format('Y-m-d');
            $attendance = $attendances->firstWhere('attendance_at', $formattedDate);
            $dayOfWeekJP = ['日', '月', '火', '水', '木', '金', '土'][$date->dayOfWeek];
            if ($attendance) {
                // 出勤時刻
                $attendance->formatted_clock_in = $attendance->clock_in
                    ? Carbon::parse($attendance->clock_in)->format('H:i')
                    : '';

                // 退勤時刻
                $attendance->formatted_clock_out = $attendance->clock_out
                    ? Carbon::parse($attendance->clock_out)->format('H:i')
                    : '';

                // 休憩時間の合計（未終了の休憩は除外）
                $attendance->total_break = BreakTime::where('attendance_id', $attendance->id)
                    ->whereNotNull('break_out')
                    ->get()
                    ->sum(function ($break) {
                        return Carbon::parse($break->break_out)->diffInMinutes($break->break_in);
                });
                $time = Carbon::createFromTimestampUTC($attendance->total_break * 60);
                $attendance->formatted_total_break = $time->format('H:i');

                // 勤務時間の合計（退勤時刻 - 出勤時刻 - 休憩時間）
                if ($attendance->clock_out) {
                    $total_work_minutes  = Carbon::parse($attendance->formatted_clock_out)->diffInMinutes($attendance->formatted_clock_in ) - $attendance->total_break;
                    $attendance->formatted_total_work = gmdate('H:i', max($total_work_minutes * 60, 0));
                } else {
                    $attendance->formatted_total_work = '';
                }
            }
            $daysInMonth[$formattedDate] = $attendance;
        }

        return view('attendance.index', compact('user', 'daysInMonth', 'currentDate', 'prevMonth', 'nextMonth'));
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
