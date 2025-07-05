<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveCategoryIdFromPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            // category_idカラムを削除
            $table->dropForeign(['category_id']); // 外部キー制約を削除
            $table->dropColumn('category_id'); // カラムを削除
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            //category_idカラムを再追加
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
        });
    }
}
