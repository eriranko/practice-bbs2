<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReplyController extends Controller
{
    // コメントへの返信を削除する
    public function destroy($id)
    {
        $reply = Reply::findOrFail($id);

        // ユーザーが自分の返信を削除できるか確認
        if (auth()->id() === $reply->user_id) {
            $reply->delete();
            return redirect()->back()->with('success', '返信が削除されました。');
        }

        return redirect()->back()->with('error', '削除権限がありません。');
    }
}
