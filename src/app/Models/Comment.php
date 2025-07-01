<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    // テーブル名を指定（省略可能）
    protected $table = 'comments';

    protected $fillable = [
        'post_id',
        'user_id',
        'content',
    ];

    // 投稿とのリレーション
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    // ユーザーとのリレーション
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 親コメントとのリレーション
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    // 子コメントとのリレーション
    public function children()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    //　親コメントと子コメントのリレーション
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id'); // 親コメントに対する返信
    }
}
