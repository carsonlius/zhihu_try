<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->string('title')->comment('标题');
            $table->text('body')->comment('问题内容');
            $table->integer('flowers_count')->default(0)->comment('关注的数目');
            $table->integer('comments_count')->default(1)->comment('评论的数目');
            $table->string('close_comment', 8)->default('F')->comment('评论的状态 F可以评论 T关闭评论');
            $table->string('is_hidden', 8)->default('F')->comment('问题状态  F正常 T隐藏删除');
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('questions');
    }
}
