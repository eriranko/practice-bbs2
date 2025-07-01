<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNicknameAndCreatedAtToCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->string('nickname')->nullable(); // ニックネーム用カラム
            $table->unsignedInteger('likes')->default(0); // いいねカウント用のカラムを追加
            $table->unsignedInteger('agree')->default(0); // なるほどカウント用のカラムを追加
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropColumn('nickname');// ニックネームカラムを削除
            $table->dropColumn('likes'); // いいねカウントカラムを削除
            $table->dropColumn('agree'); // なるほどカウントカラムを削除
        });
    }
}
