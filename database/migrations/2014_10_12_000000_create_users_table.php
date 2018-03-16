<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('avatar')->comment('头像')->nullable();
            $table->string('confirmation_token')->nullable()->comment('邮箱验证的token');
            $table->smallInteger('is_active')->default(0)->comment('邮箱是不是被激活 0没有被激活,1邮箱已经被激活');
            $table->integer('questions_count')->default(0)->comment('提问数');
            $table->integer('answers_count')->default(0)->comment('回答数');
            $table->integer('comments_count')->default(0)->comment('评论数');
            $table->integer('favorites_count')->default(0)->comment('收藏数');
            $table->integer('likes_count')->default(0)->comment('点赞数');
            $table->integer('followers_count')->default(0)->comment('关注数');
            $table->integer('following_count')->default(0)->comment('被关注数');
            $table->json('settings')->nullable()->comment('账户的设置信息');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
