@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/common/auth-form.css')}}">
<link rel="stylesheet" href="{{ asset('css/auth/register.css')}}">
@endsection

@section('content')
<div class="auth-form">
  <h2 class="auth-form__heading">会員登録</h2>
  <form class="auth-form__form" action="/register" method="post">
    @csrf
    <div class="auth-form__group">
      <label class="auth-form__label" for="name">ユーザー名</label>
      <input class="auth-form__input" type="text" name="name" id="name" value="{{ old('name') }}">
      <p class="auth-form__error-message">
        @error('name')
        {{ $message }}
        @enderror
      </p>
    </div>
    <div class="auth-form__group">
      <label class="auth-form__label" for="email">メールアドレス</label>
      <input class="auth-form__input" type="email" name="email" id="email" value="{{ old('email') }}">
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
      <label class="auth-form__label" for="password_confirmation">確認用パスワード</label>
      <input class="auth-form__input" type="password" name="password_confirmation" id="password_confirmation">
      <p class="auth-form__error-message">
      </p>
    </div>
    <div class="auth-form__group">
      <input class="auth-form__button" type="submit" value="登録する">
    </div>
    <a class="auth-form__link" href="/login">ログインはこちら</a>
  </form>
</div>
@endsection('content')