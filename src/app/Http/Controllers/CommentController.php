<?php

namespace App\Http\Controllers;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only('store'); // storeメソッドに認証ミドルウェアを適用
    }

    public function store(Request $request)
    {
        // バリデーション
        $request->validate([
        'content' => 'required|string|max:500',
        'nickname' => 'required|string|max:255', // ニックネームのバリデーション
        ], [
            'content.required' => 'コメントは必須です。',
            'nickname.required' => 'ニックネームは必須です。',
        ]);

        // 投稿を取得
        $post = Post::findOrFail($request->post_id);
        // コメントを作成
        $comment = new Comment();
        $comment->content = $request->content;
        $comment->post_id = $post->id; // 投稿IDを設定
        $comment->user_id = auth()->id(); // ログインユーザーのIDを設定
        $comment->nickname = $request->nickname; // ニックネームを設定
        $comment->save();


        // コメントの保存前にログを出力
        //Log::info('Saving comment: ', ['content' => $comment->content]);

        // リダイレクトまたはJSONレスポンス
        return response()->json(['success' => true, 'comment' => $comment]); // 適切なルートにリダイレクト
    }

    //コメントにいいねする
    public function like($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->increment('likes');
        return response()->json(['success' => true, 'likes' => $comment->likes]);
    }

    //コメントになるほどする
    public function agree($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->increment('agree');
        return response()->json(['success' => true, 'agree' => $comment->agree]);
    }

    //コメントを削除する
    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);

        // ユーザーがコメントの所有者または投稿者であることを確認
        if (auth()->user()->id !== $comment->user_id && auth()->user()->id !== $comment->post->user_id) {
            return response()->json(['success' => false, 'message' => '権限がありません。'], 403);
        }

        $comment->delete();
        return response()->json(['success' => true, 'message' => 'コメントが削除されました。']);
    }

    //コメントに返信する
        public function reply(Request $request, $commentId)
    {
        // バリデーション
        $request->validate([
            'reply_content' => 'required|string|max:255',
        ]);

        // コメントの取得
        $comment = Comment::findOrFail($commentId);

        // 返信を作成
        $reply = new Comment();
        $reply->content = $request->input('reply_content');
        $reply->nickname = auth()->user()->nickname; // ログインユーザーのニックネーム
        $reply->post_id = $comment->post_id; // 元の投稿のID
        $reply->parent_id = $comment->id; // 親コメントのID
        $reply->user_id = auth()->id(); // ログインユーザーのID
        $reply->save();

        return redirect()->back()->with('success', '返信が追加されました。');
    }


}
