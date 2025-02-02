@extends('layouts.app')
@extends('layouts.link')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/staff/index.css')}}">
@endsection

@section('content')
<div class="staff__content">
  <h1 class="staff__header">勤怠一覧</h1>

  <!-- 勤怠データの表示 -->
  <table class="staff-table">
    <thead>
      <tr>
        <th class="staff-table__th">名前</th>
        <th class="staff-table__th">メールアドレス</th>
        <th class="staff-table__th">月次勤怠</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($staffList as $staff)
      <tr>
        <td class="staff-table__td--name">{{ $staff['last_name'] . $staff['first_name'] }}</td>
        <td class="staff-table__td--email">{{ $staff['email'] }}</td>
        <td class="staff-table__td--link">
          <a href="http://localhost/admin/attendance/staff/2" class="staff-table__td--detail">詳細</a>
        </td>
      </tr>
      @endforeach
    </tbody>


</div>
@endsection('content')