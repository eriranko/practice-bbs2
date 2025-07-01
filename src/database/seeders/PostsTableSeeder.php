<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        DB::table('posts')->insert([
            [
                'user_id' => 1,
                'title' => '初めての投稿',
                'category_id' => 3,
                'content' => 'これは私の初めての投稿です。',
                'like_count' => 5,
                'agree_count' => 10,
                'attachment' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1,
                'title' => 'Laravelの基本',
                'category_id' => 1,
                'content' => 'LaravelはPHPのフレームワークです。',
                'like_count' => 10,
                'agree_count' => 10,
                'attachment' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2, // ここは実際のユーザーIDに置き換えてください
                'title' => 'データベースの操作',
                'category_id' => 2, // ここは実際のカテゴリIDに置き換えてください
                'content' => 'Eloquent ORMを使用してデータベースを操作します。',
                'like_count' => 3,
                'agree_count' => 5,
                'attachment' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'title' => 'シーダーの使い方',
                'category_id' => 3, // ここは実際のカテゴリIDに置き換えてください
                'content' => 'シーダーを使ってダミーデータを挿入します。',
                'like_count' => 7,
                'agree_count' => 15,
                'attachment' => 'example.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1,
                'title' => 'Laravelのリレーション',
                'category_id' => 1,
                'content' => 'リレーションを使ってモデル同士を関連付けます。',
                'like_count' => 2,
                'agree_count' => 0,
                'attachment' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
