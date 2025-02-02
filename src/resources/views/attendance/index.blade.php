@extends('layouts.app')
@extends('layouts.link')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance/index.css')}}">
@endsection

@section('content')
<div class="attendance__content">
  <h1 class="attendance__header">勤怠一覧</h1>

  <div class="month-selector">
    <a class="month-selector__link" href="{{ route('attendance.list', ['year' => $prevMonth->year, 'month' => $prevMonth->month]) }}">
      <img class="month-selector__image--arrow" src="/images/arrow-back.png" />前月
    </a>
    <div class="month-selector__group">
      <img class="month-selector__image--calendar" src="/images/calendar.png" />
      <h2 class="month-selector__header">{{ $currentDate->format('Y/m') }}</h2>
    </div>
    <a class="month-selector__link" href="{{ route('attendance.list', ['year' => $nextMonth->year, 'month' => $nextMonth->month]) }}">
      翌月<img class="month-selector__image--arrow" src="/images/arrow-forward.png" />
    </a>
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
      @foreach($daysInMonth as $date => $attendance)
      @php
      $dayOfWeekJP = ['日', '月', '火', '水', '木', '金', '土'][\Carbon\Carbon::parse($date)->dayOfWeek];
      @endphp
      <tr>
        <td>{{ \Carbon\Carbon::parse($date)->format('m/d') }}({{ $dayOfWeekJP }})</td>
        <td>{{ $attendance ? $attendance->formatted_clock_in : '' }}</td>
        <td>{{ $attendance ? $attendance->formatted_clock_out : '' }}</td>
        <td>{{ $attendance ? $attendance->formatted_total_break : '' }}</td>
        <td>{{ $attendance ? $attendance->formatted_total_work : '' }}</td>
        <td>
          @if ($attendance)
          <a href="{{ route('attendance.show', ['attendance_id' => $attendance->id]) }}" class="attendance-table__link">詳細</a>
          @else
          @endif
        </td>
      </tr>
      @endforeach
    </tbody>


</div>
@endsection('content')