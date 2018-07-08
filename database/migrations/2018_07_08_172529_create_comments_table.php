<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->comment('发起评论的用户ID');
            $table->text('body')->comment('评论内容');
            $table->unsignedInteger('commentable_id')->comment('关联模型的ID值');
            $table->string('commentable_type')->comment('1问题 2答案');
            $table->unsignedInteger('parent_id')->nullable()->comment('父级ID');
            $table->unsignedSmallInteger('level')->default(1)->comment('当前评论的层级');
            $table->string('is_hidden', 8)->default('F')->comment('是否隐藏');
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
        Schema::dropIfExists('comments');
    }
}
