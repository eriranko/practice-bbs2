<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;
use App\Http\Requests\BbsRequest;
use Illuminate\Support\Facades\Auth;//Laravelの認証機能を使用するのに必要。Authクラスのインポート

class BbsController extends Controller
{
    //掲示板一覧表示ページ
    public function index(Request $request) {

        //投稿を取得し、各投稿に対する最新のコメントを1件取得
        $posts = Post::with(['comments' => function ($query) {
            $query->whereNull('parent_id') // 親コメントがnullのコメントのみを取得
                ->latest(); // 最新のコメントを取得
        }])->get();

        // 各投稿に対して最新のコメントを取得
        foreach ($posts as $post) {
            $post->latest_comment = $post->comments->first(); // 最新のコメントを追加
        }

        //ビューにデータを渡す。
        return view('index', compact('posts'));
    }

    // コメントの返信を取得するメソッドを追加
    public function getReplies($commentId) {

        $comment = Comment::with('replies')->findOrFail($commentId);
        return response()->json(['replies' => $comment->replies]);
    }


    //新規投稿画面表示
    public function create() {

        //Categoriesモデルを使ってカテゴリー取得
        $categories = Category::all();

        //新規登録画面表示
        return view('create', compact('categories'));
    }


    //新規投稿
    public function post(BbsRequest $request) {
        //バリデーションはBbsRequestで実施

        //新しい投稿の作成
        $post = new Post();
        $post->title = $request->input('title');
        $post->content = $request->input('content');
        $post->user_id = Auth::id(); // 認証ユーザーのIDを設定

        // カテゴリの選択がある場合、最初のカテゴリを設定
        if ($request->has('categories') && count($request->input('categories')) > 0) {
            // カテゴリーは選択されているので、何もしない
        } else {
            // カテゴリが選択されていない場合の処理（エラーメッセージなど）
            return redirect()->back()->withErrors(['categories' => '少なくとも1つのカテゴリを選択してください。']);
        }

        $post->save(); // データベースに保存

        //カテゴリの関連付け
        if ($request->has('categories')) {
            $post->categories()->attach($request->input('categories'));
        }

        //成功レスポンスを返す
        return redirect()->route('index')->with('success', '投稿が成功しました。');
    }

    //投稿削除
    public function destroy($id)
    {
        // 投稿を取得
        $post = Post::find($id);

        // 投稿が存在しない場合の処理
        if (!$post) {
            return response()->json(['success' => false, 'message' => '投稿が見つかりません。'], 404);
        }

        // 認証ユーザーが投稿の所有者であることを確認
        if ($post->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'この投稿を削除する権限がありません。'], 403);
        }

        // 投稿を削除
        $post->delete();

        // 成功レスポンスを返す
        return response()->json(['success' => true, 'message' => '投稿が削除されました。']);
    }

    //投稿にいいねする
    public function like(Request $request, $postId, $commentId = null)
    {
        $user = Auth::user();
        Log::info('Request received for like from user: ' . $user->id);
        $post = Post::findOrFail($postId);

        if ($commentId) {
            // コメントに対するいいね
            $comment = Comment::findOrFail($commentId);
            $like = Like::where('user_id', $user->id)->where('comment_id', $commentId)->first();

            if ($like) {
                return response()->json(['success' => false, 'message' => 'すでにいいねしています。']);
            }

            Like::create(['user_id' => $user->id, 'comment_id' => $commentId]);
            $likesCount = $comment->likes()->count(); // コメントに対するいいねの数を取得
        } else {
            // 投稿に対するいいね
            $like = Like::where('user_id', $user->id)->where('post_id', $postId)->first();

            if ($like) {
                return response()->json(['success' => false, 'message' => 'すでにいいねしています。']);
            }

            Like::create(['user_id' => $user->id, 'post_id' => $postId]);
            $likesCount = $post->likes()->count(); // 投稿に対するいいねの数を取得
        }

        return response()->json(['success' => true, 'likes_count' => $likesCount]); // likes_countを返す
    }


    // 投稿になるほどする
    public function agree(Request $request, $postId, $commentId = null)
    {
        $user = Auth::user();
        $post = Post::findOrFail($postId);

        if ($commentId) {
            // コメントに対する「なるほど」
            $comment = Comment::findOrFail($commentId);
            $agree = Agree::where('user_id', $user->id)->where('comment_id', $commentId)->first();

            if ($agree) {
                return response()->json(['success' => false, 'message' => 'すでに「なるほど」しています。']);
            }

            Agree::create(['user_id' => $user->id, 'comment_id' => $commentId]);
            $agreeCount = $comment->agrees()->count(); // コメントに対する「なるほど」の数を取得
        } else {
            // 投稿に対する「なるほど」
            $agree = Agree::where('user_id', $user->id)->where('post_id', $postId)->first();

            if ($agree) {
                return response()->json(['success' => false, 'message' => 'すでに「なるほど」しています。']);
            }

        Agree::create(['user_id' => $user->id, 'post_id' => $postId]);
        $agreeCount = $post->agrees()->count(); // 投稿に対する「なるほど」の数を取得
        }

        return response()->json(['success' => true, 'agree_count' => $agreeCount]); // agree_countを返す
    }

    // 投稿詳細を表示
    public function show($id)
    {
        $post = Post::with(['comments.replies', 'comments.likes', 'comments.agrees'])->findOrFail($id); // 投稿とそのコメント、返信を取得
        return view('post', compact('post'));
    }

}
