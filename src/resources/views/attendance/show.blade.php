@extends('layouts.app')
@extends('layouts.link')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance/show.css')}}">
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
  <form class="detail-form__form" action="/attendance/stamp_correction_request" method="post" novalidate>
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
              class="detail-form__input--{{$isSubmitted ? 'inactive' : 'active'}}"
              type="text"
              name="year"
              value="{{ old('year') ?? $attendance->formatted_year }}"
              {{ $isSubmitted ? 'readonly' : ''}}>
          </td>
          <td></td>
          <td>
            <input
              class="detail-form__input--{{$isSubmitted ? 'inactive' : 'active'}}"
              type="text"
              name="date"
              value="{{ old('date') ?? $attendance->formatted_date }}"
              {{ $isSubmitted ? 'readonly' : ''}}>
          </td>
        </tr>
        <tr>
          <th>出勤・退勤</th>
          <td class="start-time">
            <input
              class="detail-form__input--{{$isSubmitted ? 'inactive' : 'active'}}"
              type="text"
              name="clock_in"
              value="{{ old('clock_in') ?? $attendance->formatted_clock_in }}"
              {{ $isSubmitted ? 'readonly' : ''}}>
          </td>
          <td class="tilde">～</td>
          <td class="end-time">
            <input
              class="detail-form__input--{{$isSubmitted ? 'inactive' : 'active'}}"
              type="text"
              name="clock_out"
              value="{{ old('clock_out') ?? $attendance->formatted_clock_out }}"
              {{ $isSubmitted ? 'readonly' : ''}}>
          </td>
        </tr>
        @foreach($breakTimes as $index => $break)
        <tr>
          <th>休憩{{$index + 1}}</th>
          <td class="start-time">
            <input
              class="detail-form__input--{{$isSubmitted ? 'inactive' : 'active'}}"
              type="text"
              name="breakTimes[{{ $break->id }}][break_in]"
              value="{{ old("breakTimes.{$break->id}.break_in", $break->break_in) }}"
              {{ $isSubmitted ? 'readonly' : ''}}>
          </td>
          <td class="tilde">～</td>
          <td class="end-time">
            <input
              class="detail-form__input--{{$isSubmitted ? 'inactive' : 'active'}}"
              type="text"
              name="breakTimes[{{ $break->id }}][break_out]"
              value="{{ old("breakTimes.{$break->id}.break_out", $break->break_out) }}"
              {{ $isSubmitted ? 'readonly' : ''}}>
          </td>
        </tr>
        @endforeach
        <!-- 空欄の休憩欄を追加 -->
        @if(!$isSubmitted)
        <tr>
          <th>休憩{{ count($breakTimes) + 1 }}</th>
          <td class="start-time">
            <input
              class="detail-form__input--{{$isSubmitted ? 'inactive' : 'active'}}"
              type="text"
              name="breakTimes[new][break_in]"
              value="{{ old('breakTimes.new.break_in') }}"
              {{ $isSubmitted ? 'readonly' : ''}}>
          </td>
          <td class="tilde">～</td>
          <td class="end-time">
            <input
              class="detail-form__input--{{$isSubmitted ? 'inactive' : 'active'}}"
              type="text"
              name="breakTimes[new][break_out]"
              value="{{ old('breakTimes.new.break_out') }}"
              {{ $isSubmitted ? 'readonly' : ''}}>
          </td>
        </tr>
        @endif
        <tr>
          <th>備考</th>
          <td colspan="3">
            <textarea
              class="detail-form__textarea--{{$isSubmitted ? 'inactive' : 'active'}}"
              type="text"
              name="note"
              {{ $isSubmitted ? 'readonly' : ''}}>{{ old('note') ?? $attendance->note }}</textarea>
          </td>
        </tr>
      </tbody>
    </table>
    <input type="hidden" name="attendance_id" value="{{ $attendance->id }}">
    <input type="hidden" name="updated_at" value="{{ $attendance->updated_at }}">
    @if($isSubmitted)
    <p class="detail-form__text--submitted">*{{$attendance->status->name}}のため修正はできません。</p>
    @else
    <input class="detail-form__button" type="submit" value="修正">
    @endif
  </form>
</div>
@endsection('content')