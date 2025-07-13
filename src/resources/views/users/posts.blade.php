@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="content__title">
        <h2>{{ $user->nickname }}の投稿一覧</h2>
    </div>
    <div class="post-frame">
        @foreach($posts as $post)
            <div class="post" data-id="{{$post->id}}">
                <div class="head__wrapper">
                    <div class="post__header">
                        <h3 class="post__title">
                            <a href="{{ route('posts.show', $post->id) }}">{{ $post->title }}</a>
                        </h3>
                        <div class="post__nickname">
                            <p class="post__nickname-inner">投稿者：{{ $post->user->nickname }}</p>
                        </div>
                        <div class="post__category">
                            @if ($post->categories->isNotEmpty())
                                <p class="post__category-inner">カテゴリー：{{ $post->categories->first()->name }}</p>
                            @else
                                <p class="post__category-inner">カテゴリー：なし</p>
                            @endif
                        </div>
                    </div>
                    <div class="post__create">
                        <p class="post__create-inner">{{ $post->created_at }}</p>
                    </div>
                    <div class="post__content">
                        <p class="post__content-inner">{{ $post->content }}</p>
                    </div>
                    @if($post->attachment)
                        <p><a class="post__attachment" href="{{ Storage::url($post->attachment) }}" target="_blank">添付ファイルを表示</a></p>
                    @endif
                    <!-- いいね機能 -->
                    <div class="like-post">
                        <button onclick="likePost({{ $post->id }})">いいね</button>
                        <span id="likes-count-{{ $post->id }}">{{ $post->likes_count }}</span>
                    </div>
                    <!-- なるほど機能 -->
                    <div class="agree-post">
                        <button onclick="agreePost({{ $post->id }})">なるほど</button>
                        <span id="agree-count-{{ $post->id }}">{{ $post->agree_count }}</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection