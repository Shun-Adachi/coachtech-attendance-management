@extends('layouts.app')
@extends('layouts.link')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/applications/approve.css')}}">
@endsection

@section('content')
<div class="attendance__content">
  <h1 class="attendance__header">勤怠詳細</h1>

  <!-- 勤怠の詳細表示 -->
  <form class="detail-form__form" action="/login" method="post" novalidate>
    @csrf
    <table class="detail-table">
      <tbody>
        <tr>
          <th>名前</th>
          <td colspan="3">
            <p class="detail-form__text">西　玲奈</p>
          </td>
        </tr>
        <tr>
          <th>日付</th>
          <td><input class="detail-form__input" type="text" name="year" value="2023年" readonly> </td>
          <td></td>
          <td><input class="detail-form__input" type="text" name="month" value="6月1日" readonly></td>
        </tr>
        <tr>
          <th>出勤・退勤</th>
          <td class="start-time"><input class="detail-form__input" type="text" name="clock_in" value="09:00" readonly></td>
          <td class="tilde">～</td>
          <td class="end-time"><input class="detail-form__input" type="text" name="clock_out" value="18:00" readonly></td>
        </tr>
        <tr>
          <th>休憩</th>
          <td class="start-time"><input class="detail-form__input" type="text" name="break_in" value="12:00" readonly></td>
          <td class="tilde">～</td>
          <td class="end-time"><input class="detail-form__input" type="text" name="break_in" value="13:00" readonly></td>
        </tr>
        <tr>
          <th>備考</th>
          <td colspan="3">
            <textarea class="detail-form__textarea" type="text" name="note" readonly oninput="this.style.height = ''; this.style.height = 200 + 'px';">{{ $attendanceData['01']['note'] }}</textarea>
          </td>
        </tr>
      </tbody>
    </table>
    <input class="detail-form__button" type="submit" value="承認">
  </form>
</div>
@endsection('content')