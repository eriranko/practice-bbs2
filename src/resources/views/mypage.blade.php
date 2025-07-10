@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="content__title">
        <h2>{{ $nickname }}さんのマイページ</h2>
    </div>
    <div class="post-frame">
        @if($posts->isEmpty())
            <p>投稿はありません。</p>
        @else
            @foreach($posts as $post)
                <div class="post" data-id="{{ $post->id }}">
                    <div class="head__wrapper">
                        <div class="post__header">
                            <h3 class="post__title">
                                <a href="{{ route('posts.show', $post->id) }}">{{ $post->title }}</a>
                            </h3>
                            <div class="post__create">
                                <p class="post__create-inner">{{ $post->created_at }}</p>
                            </div>
                            <div class="post__content">
                                <p class="post__content-inner">{{ $post->content }}</p>
                            </div>
                            @if($post->attachment)
                                <p><a class="post__attachment" href="{{ Storage::url($post->attachment) }}" target="_blank">添付ファイルを表示</a></p>
                            @endif
                            <!-- 投稿削除機能 -->
                            @if(Auth::check() && Auth::id() === $post->user_id)
                            <div class="delete-post">
                                <form action="{{ route('post.destroy', $post->id) }}" method="POST" class="delete__form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="delete-button">削除する</button>
                                </form>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>
@endsection

@section('script')
    <script src="{{ asset('js/app.js') }}"></script>
@endsection