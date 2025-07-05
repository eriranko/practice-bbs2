<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    // テーブル名を指定（省略可能）
    protected $table = 'posts';

    // 編集可能な属性
    protected $fillable = [
        'user_id',
        'title',
        'content',
        'like_count',
        'agree_count',
        'attachment',
    ];

    // ユーザーとのリレーション
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // カテゴリーとのリレーション
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // コメントとのリレーション。Postに関連するコメントを取得する
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    //Agreeモデルとのリレーション
    public function agrees()
    {
        return $this->hasMany(Agree::class);
    }

    //Likeモデルとのリレーション
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    // likes_countとagree_countを取得する
    public function getLikesCountAttribute()
    {
        return $this->likes()->count();
    }

    public function getAgreeCountAttribute()
    {
        return $this->agrees()->count();
    }

    // カテゴリとの多対多リレーション
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_post');
    }
}
