<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BbsController;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\CreateNewUser;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () {return view('welcome');});


// 認証関連のルート
Route::middleware(['guest'])->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    // ユーザー登録のルート設定
    Route::get('register', function () {
        return view('auth.register');
    })->name('register');
});

// ホーム画面表示
Route::get('/', [BbsController::class, 'index'])->name('index');

// 投稿詳細ページへのルート
Route::get('/posts/{id}', [BbsController::class, 'show'])->name('posts.show');

// 認証ミドルウェア適用
Route::middleware(['auth'])->group(function () {
    // 新規投稿画面表示
    Route::get('/create', [BbsController::class, 'create'])->name('post.create');

    // 新規投稿を作成
    Route::post('/posts', [BbsController::class, 'post'])->name('post.store');

    // 投稿削除
    Route::delete('/posts/{id}', [BbsController::class, 'destroy'])->name('post.destroy');

    // 投稿に対するいいねのルート
    Route::post('/posts/{post}/like', [BbsController::class, 'like'])->name('posts.like');

    // 投稿に対するなるほどのルート
    Route::post('/posts/{post}/agree', [BbsController::class, 'agree'])->name('posts.agree');

    // コメントを保存する
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');

    // コメント削除
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // コメントの返信ルートを追加
    Route::post('/comments/{comment}/reply', [CommentController::class, 'reply'])->name('comments.reply');

    // コメントへの返信を削除する
    Route::delete('/replies/{reply}', [ReplyController::class, 'destroy'])->name('replies.destroy');

    // コメントに「いいね」をする
    Route::post('/comments/{comment}/like', [BbsController::class, 'like'])->name('comments.like');

    // コメントに「なるほど」をする
    Route::post('/comments/{comment}/agree', [BbsController::class, 'agree'])->name('comments.agree');

    // マイページを表示する
    Route::get('/mypage', [UserController::class, 'mypage'])->name('mypage')->middleware('auth');
});
