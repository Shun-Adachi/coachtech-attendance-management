@extends('layouts.app')
@extends('layouts.link')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/staff/attendance/index.css')}}">
@endsection

@section('content')
<div class="attendance__content">
  <h1 class="attendance__header">{{$staff->last_name . $staff->first_name}}さんの勤怠</h1>

  <div class="month-selector">
    <a class="month-selector__link" href="/attendance">←前月</a>
    <div class="month-selector__group">
      <img class="month-selector__image" src="/images/calendar.png" />
      <h2 class="month-selector__header">{{ $currentDate->format('Y/m') }}</h2>
    </div>
    <a class="month-selector__link" href="/attendance">翌月→</a>
  </div>
  <!-- 勤怠データの表示 -->
  <table class="attendance-table">
    <thead>
      <tr>
        <th>日付</th>
        <th>出勤</th>
        <th>退勤</th>
        <th>休憩</th>
        <th>合計</th>
        <th>詳細</th>
      </tr>
    </thead>
    <tbody>
      @foreach (range(1, $currentDate->daysInMonth) as $day)
      @php
      $dayStr = str_pad($day, 2, '0', STR_PAD_LEFT);
      $attendance = $attendanceData[$dayStr] ?? null;
      $date = $currentDate->day($day);
      $dayOfWeek = $date->isoFormat('dd'); // 日本語の曜日
      @endphp
      <tr>
        <td>{{ $currentDate->format('m/d') }}({{ $dayOfWeek }})</td>
        <td>{{ $attendance['start'] ?? '-' }}</td>
        <td>{{ $attendance['end'] ?? '-' }}</td>
        <td>{{ $attendance['break'] ?? '-' }}</td>
        <td>{{ $attendance['total'] ?? '-' }}</td>
        <td>
          @if ($attendance)
          <a href="http://localhost/attendance/1" class="attendance-table__link">詳細</a>
          @else
          -
          @endif
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
  <form action="{{'/export?'.http_build_query(request()->query())}}" method="post">
    @csrf
    <input class="export__button" type="submit" value="CSV出力">
  </form>
</div>
@endsection('content')