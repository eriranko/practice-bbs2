@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endsection

@section('content')
<div class="register-form__content">
    <div class="register-form__heading">
        <h2>会員登録</h2>
    </div>
    <form action="/register" class="form" method="post">
        @csrf
        <!--エラーメッセージ表示-->
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <div class="form__group">
            <div class="form__group-title">
                <label for="last_name">姓:</label>
            </div>
            <div class="form__group-content">
                <div class="form__input--text">
                    <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}">
                </div>
                <div class="form__error">
                    @error('last_name')
                    {{ $message }}
                    @enderror
                </div>
            </div>
            <div class="form__group-title">
                <label for="first_name">名:</label>
            </div>
            <div class="form__group-content">
                <div class="form__input--text">
                    <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}">
                </div>
                <div class="form__error">
                    @error('first_name')
                    {{ $message }}
                    @enderror
                </div>
            </div>
            <div class="form__group-title">
                <label for="nickname">表示名:</label>
            </div>
            <div class="form__group-content">
                <div class="form__input--text">
                    <input type="text" id="nickname" name="nickname" value="{{ old('nickname') }}">
                </div>
                <div class="form__error">
                    @error('nickname')
                    {{ $message }}
                    @enderror
                </div>
            </div>
            <div class="form__group-title">
                <label for="gender">性別:</label>
            </div>
            <div class="form__input--select">
                <select id="gender" name="gender">
                    <option value="male">男性</option>
                    <option value="female">女性</option>
                    <option value="other">その他</option>
                </select>
            </div>
            <div class="form__group-title">
                <label for="email">メールアドレス:</label>
            </div>
            <div class="form__group-content">
                <div class="form__input--text">
                    <input type="email" id="email" name="email" value="{{ old('email') }}">
                </div>
                <div class="form__error">
                    @error('email')
                    {{ $message }}
                    @enderror
                </div>
            </div>
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
            <div class="form__group-title">
                <span class="form__label--item">確認用パスワード</span>
            </div>
            <div class="form__group-content">
                <div class="form__input--text">
                    <input type="password" id="password_confirmation"  name="password_confirmation">
                </div>
            </div>
        </div>
        <div class="form__button">
            <button class="form__button-submit" type="submit">登録</button>
        </div>
    </form>
    <div class="login__link">
        <a class="login__button-submit" href="/login">ログインの方はこちら</a>
    </div>
</div>
@endsection