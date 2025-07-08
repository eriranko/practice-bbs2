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
                        <button onclick="deletePost({{ $post->id }})">削除する</button>
                    </div>
                    @endif
                </div>

                <div class="comment__wrapper">
                    <h4 class="comment__title">最新のコメント</h4>

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

                    <!-- 最新のコメントを表示 -->
                    @if ($post->latest_comment)
                        <div class="comment">
                            <strong>{{ $post->latest_comment->nickname }}</strong>
                            ({{ $post->latest_comment->created_at->format('Y/m/d H:i') }}): {{ $post->latest_comment->content }}
                        </div>
                    @else
                        <p>コメントはありません。</p>
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

                    <!-- コメントが2件以上ある場合の表示 -->
                    @if ($post->comments->count() > 1)
                        <a href="{{ route('posts.show', $post->id) }}" class="btn">全てのコメントを見る</a>
                    @endif


                </div>
            </div>
        @endforeach
    </div>
</div>

@endsection

@section('script')
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
