@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/common/auth-form.css')}}">
<link rel="stylesheet" href="{{ asset('css/auth/admin-login.css')}}">
@endsection

@section('content')
<div class="auth-form">
  <h2 class="auth-form__heading">管理者ログイン</h2>
  <form class="auth-form__form" action="/login" method="post" novalidate>
    @csrf
    <div class="auth-form__group">
      <label class="auth-form__label" for="email">メールアドレス</label>
      <input class="auth-form__input" type="text" name="email" id="email" value="{{ old('email') }}">
      <p class="auth-form__error-message">
        @error('email')
        {{ $message }}
        @enderror
      </p>
    </div>
    <div class="auth-form__group">
      <label class="auth-form__label" for="password">パスワード</label>
      <input class="auth-form__input" type="password" name="password" id="password">
      <p class="auth-form__error-message">
        @error('password')
        {{ $message }}
        @enderror
      </p>
    </div>
    <div class="auth-form__group">
      <input class="auth-form__button" type="submit" value="管理者ログインする">
    </div>
  </form>
</div>
@endsection('content')