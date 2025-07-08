@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="post-detail">
        <h2>{{ $post->title }}</h2>
        <p>投稿者: {{ $post->user->nickname }}</p>
        <p>投稿日: {{ $post->created_at }}</p>
        <p>{{ $post->content }}</p>

        @if($post->attachment)
            <p><a href="{{ Storage::url($post->attachment) }}" target="_blank">添付ファイルを表示</a></p>
        @endif

        <!-- コメント投稿フォーム -->
        @if (auth()->check())
            <form class="comment__form" action="{{ route('comments.store', $post->id) }}" method="POST">
                @csrf
                <input type="hidden" name="post_id" value="{{ $post->id }}">
                <input type="hidden" name="nickname" value="{{ auth()->user()->nickname }}">
                <textarea class="comment__add" name="content" placeholder="コメントを追加"></textarea>
                <button class="comment__add-submit" type="submit">送信する</button>
            </form>
        @else
            <p>コメントするにはログインしてください</p>
        @endif

        <h4>全てのコメント</h4>
        <div class="comments">
        @foreach($post->comments as $comment)
            <div class="comment" id="comment-{{ $comment->id }}">
                <p>
                    <strong>{{ $comment->nickname }}</strong> ({{ $comment->created_at->format('Y/m/d H:i') }}): {{ $comment->content }}
                </p>
                <div class="comment-button">
                    <button class="like-button" data-id="{{ $comment->id }}" onclick="likeComment({{ $comment->id }})">いいね (<span id="like-count-{{ $comment->id }}">{{ $comment->likes_count }}</span>)</button>
                    <button class="agree-button" data-id="{{ $comment->id }}" onclick="agreeComment({{ $comment->id }})">なるほど (<span id="agree-count-{{ $comment->id }}">{{ $comment->agrees_count }}</span>)</button>
                    <button class="reply-toggle" onclick="toggleReplies({{ $comment->id }}, this)">コメントへの返信（{{ $comment->replies->count() }}）を表示</button>
                    @if (auth()->check() && (auth()->id() === $comment->user_id || auth()->id() === $comment->post->user_id))
                        <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" class="delete__form">
                        @csrf
                        @method('DELETE')
                            <button type="submit" class="delete-button">コメントを削除する</button>
                        </form>
                    @endif
                </div>
                <div class="reply__wrapper" id="replies-{{ $comment->id }}" style="display:none;">
                    @foreach($comment->replies as $reply)
                        <div class="reply">
                            <strong>{{ $reply->nickname }}</strong> ({{ $reply->created_at->format('Y/m/d H:i') }}): {{ $reply->content }}
                            @if (auth()->check() && (auth()->id() === $reply->user_id || auth()->id() === $post->user_id)) <!-- 返信の作者または投稿者が削除できる -->
                                <form action="{{ route('replies.destroy', $reply->id) }}" method="POST" class="delete__form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="delete-button">削除する</button>
                                </form>
                            @endif
                        </div>
                    @endforeach
                </div>
                <!--返信機能-->
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                @if (auth()->check())
                    <form class="reply__form" action="{{ route('comments.reply', $comment->id) }}" method="POST">
                        @csrf
                        <textarea class="reply__add" name="reply_content" placeholder="コメントに返信する内容" required></textarea>
                        <button type="submit">返信</button>
                    </form>
                @else
                    <p>返信するにはログインしてください</p>
                @endif
            </div>
        @endforeach
        </div>
    </div>
</div>

@endsection

@section('script')
    <script src="{{ asset('js/app.js') }}"></script>
@endsection