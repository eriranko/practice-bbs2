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
                            <p class="post__category-inner">カテゴリー：{{ $post->category->name }}</p>
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
<!--いいね機能-->
    <script>
        function likePost(postId) {
            fetch(`/posts/${postId}/like`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({})
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const likesCountElement = document.getElementById(`likes-count-${postId}`);
                    likesCountElement.textContent = parseInt(likesCountElement.textContent) + 1;
                }
            })
            .catch(error => console.error('Error:', error));
        }

        function likeComment(commentId) {
            fetch(`/comments/${commentId}/like`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({})
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const likeCountElement = document.getElementById(`like-count-${commentId}`);
                    likeCountElement.textContent = data.likes_count; // 新しい「いいね」の数に更新
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>

<!--なるほど機能-->
    <script>
        function agreePost(postId) {
            fetch(`/posts/${postId}/agree`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({})
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const agreeCountElement = document.getElementById(`agree-count-${postId}`);
                    agreeCountElement.textContent = parseInt(agreeCountElement.textContent) + 1;
                }
            });
        }

        function agreeComment(commentId) {
            fetch(`/comments/${commentId}/agree`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({})
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const agreeCountElement = document.getElementById(`agree-count-${commentId}`);
                    agreeCountElement.textContent = data.agree_count; // 新しい「なるほど」の数に更新
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>

<!--削除機能-->
<script>
    function deletePost(postId) {
        if (confirm('この投稿を削除しますか？')) {
            fetch(`/posts/${postId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('削除に失敗しました。');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // 投稿をDOMから削除
                    const postElement = document.querySelector(`.post[data-id="${postId}"]`);
                    postElement.remove();
                } else {
                    alert(data.message || '削除に失敗しました。');
                }
            })
            .catch(error => {
                console.error('削除処理中にエラーが発生しました:', error);
                alert('通信エラーが発生しました。');
            });
        }
    }
</script>

<!--返信機能-->
<script>
    function toggleReplies(commentId, button) {
        const repliesWrapper = document.getElementById(`replies-${commentId}`);
        if (repliesWrapper.style.display === 'none' || repliesWrapper.style.display === '') {
            repliesWrapper.style.display = 'block'; // 返信を表示
            button.textContent = 'コメントへの返信を隠す'; // ボタンのテキストを変更
        } else {
            repliesWrapper.style.display = 'none'; // 返信を非表示
            button.textContent = `コメントへの返信（${repliesWrapper.children.length}）を表示`; // ボタンのテキストを元に戻す
        }
    }
</script>

@endsection