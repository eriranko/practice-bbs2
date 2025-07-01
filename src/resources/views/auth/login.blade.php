@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection

@section('content')
    <div class="login">
        <h2>ログイン</h2>
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="form__group">
                <div class="form__group-title">
                    <label for="email">メールアドレス:</label>
                </div>
                <div class="form__group-content">
                    <div class="form__input--text">
                        <input type="email" id="email" name="email" value="{{ old('email') }}" autofocus>
                    </div>
                    <div class="form__error">
                    @error('email')
                    {{ $message }}
                    @enderror
                    </div>
                </div>
            </div>
            <div class="form__group">
                <div class="form__group-title">
                    <label for="password">パスワード:</label>
                </div>
                <div class="form__group-content">
                    <div class="form__input--text">
                        <input type="password" id="password" name="password">
                    </div>
                    <div class="form__error">
                    @error('password')
                    {{ $message }}
                    @enderror
                    </div>
                </div>
            </div>
            <div class="form__button">
                <button type="submit">ログイン</button>
            </div>
        </form>
        <div class="register__link">
            <a class="register__button-submit" href="/register">会員登録の方はこちら</a>
        </div>
    </div>
@endsection
