<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class UserController extends Controller
{
    //mypageを表示する
    public function mypage()
    {
        // ログイン中のユーザーを取得
        $user = auth()->user(); // ログインユーザーを取得
        // ログイン中のユーザーの投稿を取得
        $posts = Post::where('user_id', auth()->id())->get();

        // mypage ビューを返す
        return view('mypage', [
        'posts' => $posts,
        'nickname' => $user->nickname, // ニックネームをビューに渡す
        ]);
    }
}
