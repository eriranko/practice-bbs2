<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'post_id',
        'comment_id'
    ];

    // ユーザーとのリレーション
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 投稿とのリレーション
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    //コメントとのリレーション

    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }

}
