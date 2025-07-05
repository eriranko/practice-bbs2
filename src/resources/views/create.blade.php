@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/create.css') }}">
@endsection

@section('content')
<div class="new-post">
    <div class="title-wrapper">
        <h2>掲示板に投稿する</h2>
    </div>
    <div class="content-wrapper">
        <form class="new-post" action="/posts" method="post">
            @csrf
            <div class="post__title">
                <label for="title">タイトル：</label>
                <input type="text" id="title" name="title">
            </div>
            <div class="post__categories">
                <h3>カテゴリー：</h3>
                @foreach ($categories as $category)
                <label>
                    <input type="checkbox" name="categories[]" value="{{ $category->id }}"> {{$category->name}}
                </label>
                @endforeach
            </div>
            <div class="post__content">
                <label for="post__content-inner">内容：</label>
                <textarea name="content" id="content" rows="5"></textarea>
            </div>
            <div class="post__button">
                <button type="submit" class="post__button-submit">投稿する</button>
            </div>
        </form>
    </div>
</div>
@endsection