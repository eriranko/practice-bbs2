<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyCommentsAndPostsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // comments テーブルから agree と likes カラムを削除
        Schema::table('comments', function (Blueprint $table) {
            $table->dropColumn(['agree', 'likes']);
        });

        // posts テーブルから like_count と agree_count カラムを削除
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['like_count', 'agree_count']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // comments テーブルに agree と likes カラムを再追加
        Schema::table('comments', function (Blueprint $table) {
            $table->integer('agree')->default(0); // なるほどのカウント
            $table->integer('likes')->default(0); // いいねのカウント
        });

        // posts テーブルに like_count と agree_count カラムを再追加
        Schema::table('posts', function (Blueprint $table) {
            $table->integer('like_count')->default(0); // いいねのカウント
            $table->integer('agree_count')->default(0); // なるほどのカウント
        });
    }
}
