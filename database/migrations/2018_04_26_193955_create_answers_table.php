<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->comment('回答者');
            $table->integer('question_id')->comment('问题ID');
            $table->text('body')->comment('回答内容');
            $table->integer('votes_count')->default(0)->comment('点赞总数');
            $table->integer('comments_count')->default(0)->comment('评论总数');
            $table->string('is_hidden', 8)->default('F')->comment('是否隐藏起来 F显示 T隐藏');
            $table->string('close_comment', 8)->default('F')->comment('是否关闭评论 F允许评论 T关闭评论');

            $table->index('user_id');
            $table->index('question_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('answers');
    }
}
