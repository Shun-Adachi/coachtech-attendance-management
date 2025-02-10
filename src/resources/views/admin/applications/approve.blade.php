@extends('layouts.app')
@extends('layouts.link')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/applications/approve.css')}}">
@endsection

@section('content')
<div class="attendance__content">
  <h1 class="attendance__header">勤怠詳細</h1>


  @error('year')
    <div class="user-form__error-message">
    {{ $message }}
    </div>
  @enderror
  @error('date')
    <div class="user-form__error-message">
    {{ $message }}
    </div>
  @enderror
  @error('clock_in')
    <div class="user-form__error-message">
    {{ $message }}
    </div>
  @enderror
  @error('clock_out')
    <div class="user-form__error-message">
    {{ $message }}
    </div>
  @enderror
  @foreach($breakTimes as $index => $break)
    @error("breakTimes.$break->id.break_in")
      <div class="user-form__error-message">
      休憩{{$index + 1}}：{{ $message }}
      </div>
    @enderror
    @error("breakTimes.$break->id.break_out")
      <div class="user-form__error-message">
      休憩{{$index + 1}}：{{ $message }}
      </div>
    @enderror
  @endforeach
  @error("breakTimes.new.break_in")
    <div class="user-form__error-message">
    休憩{{count($breakTimes) + 1 }}：{{ $message }}
    </div>
  @enderror
  @error("breakTimes.new.break_out")
    <div class="user-form__error-message">
    休憩{{count($breakTimes) + 1 }}：{{ $message }}
    </div>
  @enderror
  @error('note')
    <div class="user-form__error-message">
    {{ $message }}
    </div>
  @enderror
  @error('attendance')
    <div class="user-form__error-message">
    {{ $message }}
    </div>
  @enderror

  <!-- 勤怠の詳細表示 -->
  <form class="detail-form__form" action="/stamp_correction_request/approve/{{ $attendance->id }}" method="post" novalidate>
    @csrf
    <table class="detail-table">
      <tbody>
        <tr>
          <th>名前</th>
          <td colspan="3" class="detail-form__text--name">{{ $attendance->user->name }}</td>
        </tr>
        <tr>
          <th>日付
          <td>
            <input
              class="detail-form__input--inactive"
              type="text"
              name="year"
              value="{{ $attendance->formatted_year }}"
              readonly>
          </td>
          <td></td>
          <td>
            <input
              class="detail-form__input--inactive"
              type="text"
              name="date"
              value="{{ $attendance->formatted_date }}"
              readonly>
          </td>
        </tr>
        <tr>
          <th>出勤・退勤</th>
          <td class="start-time">
            <input
              class="detail-form__input--inactive"
              type="text"
              name="clock_in"
              value="{{ $attendance->formatted_clock_in }}"
              readonly>
          </td>
          <td class="tilde">～</td>
          <td class="end-time">
            <input
              class="detail-form__input--inactive"
              type="text"
              name="clock_out"
              value="{{ $attendance->formatted_clock_out }}"
              readonly>
          </td>
        </tr>
        @foreach($breakTimes as $index => $break)
        <tr>
          <th>休憩{{$index + 1}}</th>
          <td class="start-time">
            <input
              class="detail-form__input--inactive"
              type="text"
              name="breakTimes[{{ $break->id }}][break_in]"
              value="{{ $break->break_in }}"
              readonly>
          </td>
          <td class="tilde">～</td>
          <td class="end-time">
            <input
              class="detail-form__input--inactive"
              type="text"
              name="breakTimes[{{ $break->id }}][break_out]"
              value="{{ $break->break_out }}"
              readonly>
          </td>
        </tr>
        @endforeach
        <tr>
          <th>備考</th>
          <td colspan="3">
            <textarea
              class="detail-form__textarea--inactive"
              type="text"
              name="note"
              readonly>{{ $attendance->note }}</textarea>
          </td>
        </tr>
      </tbody>
    </table>
    <input type="hidden" name="attendance_id" value="{{ $attendance->id }}">
    <input type="hidden" name="updated_at" value="{{ $attendance->updated_at }}">
    @if($isApproved)
    <input class="detail-form__button--approved" type="submit" value="承認済み">
    @else
    <input class="detail-form__button--pending" type="submit" value="承認">
    @endif
  </form>
</div>
@endsection('content')