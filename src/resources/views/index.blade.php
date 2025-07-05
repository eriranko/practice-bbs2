@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="content__title">
        <h2>投稿一覧</h2>
    </div>
    <div class="post-frame">
        @foreach($posts as $post)
            <div class="post" data-id="{{$post->id}}">
                <div class="head__wrapper">
                    <div class="post__header">
                        <h3 class="post__title">{{ $post->title }}</h3>
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
                    <!--いいね機能-->
                    <div class="like-post">
                        <button onclick="likePost({{ $post->id }})">いいね</button>
                        <!--いいねカウント-->
                        <span id="likes-count-{{ $post->id }}">{{ $post->likes_count }}</span>
                    </div>
                    <!--なるほど機能-->
                    <div class="agree-post">
                        <button onclick="agreePost({{ $post->id }})">なるほど</button>
                        <!--なるほどカウント-->
                        <span id="agree-count-{{ $post->id }}">{{ $post->agree_count }}</span>
                    </div>
                    <!-- 投稿削除機能 -->
                    @if(Auth::check() && Auth::id() === $post->user_id)
                    <div class="delete-post">
                        <button onclick="deletePost({{ $post->id }})">削除</button>
                    </div>
                    @endif
                </div>

                <div class="comment__wrapper">
                    <h4 class="comment__title">コメント</h4>

                    <!-- バリデーションエラーメッセージの表示 -->
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                        </ul>
                    </div>
                    @endif

                    @if (auth()->check())
                        <form class="comment__form" action="{{ route('comments.store', $post->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="post_id" value="{{ $post->id }}">  <!-- 投稿IDを隠しフィールドに追加 -->
                            <input type="hidden" name="nickname" value="{{ auth()->user()->nickname }}"> <!-- 自動的にニックネームを設定 -->
                            <textarea class="comment__add" name="content" placeholder="コメントを追加"></textarea>
                            <button class="comment__add-submit" type="submit">コメント</button>
                        </form>
                    @else
                        <p>コメントするにはログインしてください</p>
                    @endif

                    <div class="comments">
                        @foreach($post->comments as $comment)
                            <div class="comment" id="comment-{{ $comment->id }}">
                                <p>
                                    <strong>{{ $comment->nickname }}</strong> ({{ $comment->created_at->format('Y/m/d H:i') }}): {{ $comment->content }}
                                </p>
                                <p>
                                    <button class="like-button" data-id="{{ $comment->id }}" onclick="likeComment({{ $comment->id }})">いいね ({{ $comment->likes }})</button>
                                    <button class="agree-button" data-id="{{ $comment->id }}" onclick="agreeComment({{ $comment->id }})">なるほど ({{ $comment->agree }})</button>
                                </p>
                                <p>
                                    <button class="reply-toggle" onclick="toggleReplies({{ $comment->id }}, this)">
                                        コメントへの返信（{{ $comment->replies->count() }}）を表示
                                    </button>
                                </p>
                                <div class="reply__wrapper" id="replies-{{ $comment->id }}" style="display:none;">
                                    @foreach($comment->replies as $reply)
                                        <div class="reply">
                                            <strong>{{ $reply->nickname }}</strong> ({{ $reply->created_at->format('Y/m/d H:i') }}): {{ $reply->content }}
                                        </div>
                                    @endforeach
                                </div>
                                <!--コメント削除機能-->
                                @if (auth()->check() && (auth()->id() === $comment->user_id || auth()->id() === $comment->post->user_id))
                                    <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit">削除</button>
                                    </form>
                                @endif
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
        @endforeach
    </div>
</div>

@endsection

@section('script')
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
