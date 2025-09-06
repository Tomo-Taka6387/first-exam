@extends('layouts.auth')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/register.css')}}?v={{ time() }}">
@endsection

@section('content')
<div class="register-form">
    <h2 class="register-form_title">会員登録</h2>
    <div class="register-form__inner">
        <form class="register-form__form" action="{{ route('register.submit') }}" method="post">
            @csrf
            <div class="register-form__group">
                <label class="register-form__label" for="name">ユーザー名</label>
                <input class="register-form__input" type="text" name="name" id="name">
                <p class="register-form__error-message">
                    @error('name')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="register-form__group">
                <label class="register-form__label" for="email">メールアドレス</label>
                <input class="register-form__input" type="mail" name="email" id="email">
                <p class="register-form__error-message">
                    @error('email')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="register-form__group">
                <label class="register-form__label" for="password">パスワード</label>
                <input class="register-form__input" type="password" name="password" id="password">
                @error('password')
                @if($message !== 'パスワードと一致しません')
                <p class="register-form__error-message">{{ $message }}</p>
                @endif
                @enderror
            </div>
            <div class="register-form__group">
                <label class="register-form__label" for="password_confirmation">確認用パスワード</label>
                <input class="register-form__input" type="password" name="password_confirmation" id="password_confirmation">
                @if($errors->has('password') && $errors->first('password') === 'パスワードと一致しません')
                <p class="register-form__error-message">
                    {{ $errors->first('password') }}
                </p>
                @endif
            </div>
            <div class="form_button">
                <button class="register-form__btn btn" type="submit">登録する</button>
                <a href="/login" class="register_btn">ログインはこちら</a>
            </div>
        </form>
    </div>
</div>
@endsection