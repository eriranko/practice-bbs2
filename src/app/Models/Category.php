<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // テーブル名を指定（省略可能）
    protected $table = 'categories';

    protected $fillable = [
        'name',
        'description',
    ];

    // 投稿とのリレーション
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

}
