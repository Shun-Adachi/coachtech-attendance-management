@extends('layouts.app')
@extends('layouts.link')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/applications/index.css')}}">
@endsection

@section('content')
<div class="attendance__content">
  <h1 class="attendance__header">申請一覧</h1>

  <!-- タブ -->
  <div class="status-tab">
    <a class="status-tab__link--{{ $tab === 'approved' ? 'inactive' : 'active' }}" href="/stamp_correction_request/list">承認待ち</a>
    <a class="status-tab__link--{{ $tab === 'approved' ? 'active' : 'inactive' }}" href="/stamp_correction_request/list?tab=approved">承認済み</a>
  </div>
  <!-- 勤怠データの表示 -->
  <table class="attendance-table">
    <thead>
      <tr>
        <th class="attendance-table__text--status">状態</th>
        <th class="attendance-table__text--name">名前</th>
        <th class="attendance-table__text--date">対象日時</th>
        <th class="attendance-table__text--note">申請理由</th>
        <th class="attendance-table__text--date">申請日時</th>
        <th class="attendance-table__text--detail">詳細</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($attendances as $attendance)
      <tr>
        <td class="attendance-table__text--status">{{ $attendance->status->name }}</td>
        <td class="attendance-table__text--name">{{ $attendance->user->name }}</td>
        <td class="attendance-table__text--date">{{ $attendance->formatted_attendance_at }}</td>
        <td class="attendance-table__text--note">{{ $attendance->note }}</td>
        <td class="attendance-table__text--date">{{ $attendance->formatted_requested_at }}</td>
        <td>
          <a href="/stamp_correction_request/approve/{{ $attendance->id }}" class="attendance-table__text--detail">詳細</a>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
@endsection('content')