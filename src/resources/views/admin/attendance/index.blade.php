@extends('layouts.app')
@extends('layouts.link')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/attendance/index.css')}}">
@endsection

@section('content')
<div class="attendance__content">
  <h1 class="attendance__header">勤怠一覧</h1>

  <div class="selector">
    <a class="selector__link" href="{{ route('admin.attendance.list', ['year' => $prevDay->year, 'month' => $prevDay->month, 'day' => $prevDay->day]) }}">
      <img class="selector__image--arrow" src="/images/arrow-back.png" />前日
    </a>
    <div class="selector__group">
      <img class="selector__image--calendar" src="/images/calendar.png" />
      <h2 class="selector__header">{{ $currentDate->format('Y/m/d') }}</h2>
    </div>
    <a class="selector__link" href="{{ route('admin.attendance.list', ['year' => $nextDay->year, 'month' => $nextDay->month, 'day' => $nextDay->day]) }}">
      翌日<img class="selector__image--arrow" src="/images/arrow-forward.png" />
    </a>
  </div>
  <!-- 勤怠データの表示 -->
  <table class="attendance-table">
    <thead>
      <tr>
        <th>名前</th>
        <th>出勤</th>
        <th>退勤</th>
        <th>休憩</th>
        <th>合計</th>
        <th>詳細</th>
      </tr>
    </thead>
    <tbody>
      @foreach($attendances as $attendance)
      <tr>
        <td>{{ $attendance ? $attendance->user->name : '' }}</td>
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