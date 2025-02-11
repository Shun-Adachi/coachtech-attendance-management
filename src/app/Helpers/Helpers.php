<?php

use Carbon\Carbon;
use App\Models\BreakTime;

if (!function_exists('calculateTotalBreakMinutes')) {
    /**
     * 指定された勤怠IDの休憩時間合計（分）を計算する
     *
     * @param  int  $attendanceId
     * @return int
     */
    function calculateTotalBreakMinutes($attendanceId)
    {
        return BreakTime::where('attendance_id', $attendanceId)
            ->whereNotNull('break_in')
            ->whereNotNull('break_out')
            ->get()
            ->sum(function ($break) {
                return Carbon::parse($break->break_out)->diffInMinutes(Carbon::parse($break->break_in));
            });
    }
}

if (!function_exists('formatTotalBreakTime')) {
    /**
     * 休憩時間（分）を "H:i" 形式にフォーマットして返す
     *
     * @param  int  $totalBreakMinutes
     * @return string
     */
    function formatTotalBreakTime($totalBreakMinutes)
    {
        // Carbon::createFromTimestampUTC() は秒単位なので、分×60 して変換
        $time = Carbon::createFromTimestampUTC($totalBreakMinutes * 60);
        return $time->format('H:i');
    }
}

if (!function_exists('calculateFormattedTotalWorkTime')) {
    /**
     * 出勤・退勤時間と休憩時間を元に勤務時間の合計を "H:i" 形式で返す
     *
     * @param  string $clockIn   出勤時刻（"H:i" 形式）
     * @param  string $clockOut  退勤時刻（"H:i" 形式）
     * @param  int    $totalBreakMinutes 休憩時間の合計（分）
     * @return string
     */
    function calculateFormattedTotalWorkTime($clockIn, $clockOut, $totalBreakMinutes)
    {
        // 退勤時刻がない場合は空文字を返す
        if (!$clockOut) {
            return '';
        }
        // 出勤・退勤の時刻を Carbon オブジェクトに変換して差分を分単位で取得
        $workMinutes = Carbon::parse($clockOut)->diffInMinutes(Carbon::parse($clockIn));
        // 休憩時間を引く
        $workMinutes -= $totalBreakMinutes;
        // gmdate() で "H:i" 形式にフォーマット（負の値対策に max() を使用）
        return gmdate('H:i', max($workMinutes * 60, 0));
    }
}

if (!function_exists('createCalendarDays')) {
    /**
     * 指定された月の開始日～終了日までの日付リストを作成するヘルパー関数
     * 各日付に対応する勤怠データ（あれば）を付加して配列として返します。
     *
     * @param  \Carbon\Carbon  $startOfMonth  月初日
     * @param  \Carbon\Carbon  $endOfMonth    月末日
     * @param  \Illuminate\Support\Collection  $attendances  勤怠データのコレクション
     * @return array  キーが "Y-m-d" 形式の日付、値が対応する勤怠データ（存在しない場合は null）
     */
    function createCalendarDays(Carbon $startOfMonth, Carbon $endOfMonth, $attendances)
    {
        $days = [];
        // 月初～月末までループ
        for ($date = $startOfMonth->copy(); $date->lte($endOfMonth); $date->addDay()) {
            $formattedDate = $date->format('Y-m-d');
            // 勤怠データコレクションから attendance_at が該当の日付のデータを取得
            $attendance = $attendances->firstWhere('attendance_at', $formattedDate);

            // 勤怠データが存在する場合、フォーマット処理を実施
            if ($attendance) {
                $attendance->formatted_clock_in = $attendance->clock_in
                    ? Carbon::parse($attendance->clock_in)->format('H:i')
                    : '';
                $attendance->formatted_clock_out = $attendance->clock_out
                    ? Carbon::parse($attendance->clock_out)->format('H:i')
                    : '';

                // 休憩時間の合計（ヘルパー関数 calculateTotalBreakMinutes() などを利用）
                $totalBreak = calculateTotalBreakMinutes($attendance->id);
                $attendance->total_break = $totalBreak;
                $attendance->formatted_total_break = formatTotalBreakTime($totalBreak);
                // 勤務時間の合計（退勤 - 出勤 - 休憩時間）
                $attendance->formatted_total_work = calculateFormattedTotalWorkTime(
                    $attendance->clock_in,
                    $attendance->clock_out,
                    $totalBreak
                );
            }

            // キーを日付文字列として配列にセット
            $days[$formattedDate] = $attendance;
        }
        return $days;
    }
}