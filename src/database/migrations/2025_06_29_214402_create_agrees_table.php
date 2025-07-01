<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgreesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agrees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // ユーザーID
            $table->foreignId('post_id')->nullable()->constrained()->onDelete('cascade'); // 投稿ID
            $table->foreignId('comment_id')->nullable()->constrained()->onDelete('cascade'); // コメントID
            $table->timestamps();
        
            // ユニーク制約を追加
            $table->unique(['user_id', 'post_id']);
            $table->unique(['user_id', 'comment_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agrees');
    }
}
