@extends('layouts.app')
@extends('layouts.link')

@section('css')
<link rel="stylesheet" href="{{ asset('css/applications/index.css')}}">
@endsection

@section('content')
<div class="attendance__content">
  <h1 class="attendance__header">申請一覧</h1>

  <!-- タブ -->
  <div class="status-tab">
    <a class="status-tab__link" href="">承認待ち</a>
    <a class="status-tab__link" href="">承認済み</a>
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
        <td>{{ $user->last_name . $user->first_name }}</td>
        <td>{{ $attendance['date'] }}</td>
        <td>{{ $attendance['note']}}</td>
        <td>{{ $attendance['app_date']}}</td>
        <td>
          <a href="http://localhost/stamp_correction_request/approve/1" class="attendance-table__link">詳細</a>
        </td>
      </tr>
      @endforeach
    </tbody>
</div>
@endsection('content')