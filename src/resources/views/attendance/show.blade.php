@extends('layouts.app')
@extends('layouts.link')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance/show.css')}}">
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
          <td colspan="3">西　玲奈</td>
        </tr>
        <tr>
          <th>日付</th>
          <td><input class="detail-form__input" type="text" name="year" value="2023年"> </td>
          <td></td>
          <td><input class="detail-form__input" type="text" name="month" value="6月1日"></td>
        </tr>
        <tr>
          <th>出勤・退勤</th>
          <td class="start-time"><input class="detail-form__input" type="text" name="clock_in" value="09:00"></td>
          <td class="tilde">～</td>
          <td class="end-time"><input class="detail-form__input" type="text" name="clock_out" value="18:00"></td>
        </tr>
        <tr>
          <th>休憩</th>
          <td class="start-time"><input class="detail-form__input" type="text" name="break_in" value="12:00"></td>
          <td class="tilde">～</td>
          <td class="end-time"><input class="detail-form__input" type="text" name="break_in" value="13:00"></td>
        </tr>
        <tr>
          <th>備考</th>
          <td colspan="3"><textarea class="detail-form__textarea" type="text" name="note">電車遅延のため</textarea></td>
        </tr>
      </tbody>
    </table>
    <input class="detail-form__button" type="submit" value="修正">
  </form>
</div>
@endsection('content')