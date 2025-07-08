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

    // いいねとのリレーション
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    // なるほどとのリレーション
    public function agrees()
    {
        return $this->hasMany(Agree::class);
    }

    // いいねの数を取得するアクセサ
    public function getLikesCountAttribute()
    {
        return $this->likes()->count();
    }

    // なるほどの数を取得するアクセサ
    public function getAgreesCountAttribute()
    {
        return $this->agrees()->count();
    }
}
