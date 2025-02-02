@extends('layouts.app')
@extends('layouts.link')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/attendance/index.css')}}">
@endsection

@section('content')
<div class="attendance__content">
  <h1 class="attendance__header">勤怠一覧</h1>

  <div class="day-selector">
    <a class="day-selector__link" href="/attendance">←前日</a>
    <div class="day-selector__group">
      <img class="day-selector__image" src="/images/calendar.png" />
      <h2 class="day-selector__header">{{ $currentDate->format('Y/m/d') }}</h2>
    </div>
    <a class="day-selector__link" href="/attendance">翌日→</a>
  </div>
  <!-- 勤怠データの表示 -->
  <table class="attendance-table">
    <thead>
      <tr>
        <th>状態</th>
        <th>名前</th>
        <th>対象日時</th>
        <th>申請理由</th>
        <th>申請日時</th>
        <th>詳細</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($attendanceData as $attendance)
      <tr>
        <td>{{ $attendance['status'] }}</td>
        <td>{{ $attendance['name'] }}</td>
        <td>{{ $attendance['date'] }}</td>
        <td>{{ $attendance['note']}}</td>
        <td>{{ $attendance['app_date']}}</td>
        <td>
          <a href="http://localhost/attendance/1" class="attendance-table__link">詳細</a>
        </td>
      </tr>
      @endforeach
    </tbody>


</div>
@endsection('content')