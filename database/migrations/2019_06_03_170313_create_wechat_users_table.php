<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWechatUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('wechat_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 30)->comment('小程序的用户名');
            $table->string('wechat_id')->comment('微信唯一标识');
            $table->string('country', 10)->comment('国家');
            $table->string('province', 30)->comment('省份');
            $table->string('city', 30)->comment('城市');
            $table->text('avatar_url')->comment('头像');
            $table->integer('fav_nums')->default(0)->comment('点赞次数');
            $table->integer('comment_nums')->default(0)->comment('短评次数');
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
        Schema::dropIfExists('wechat_users');
    }
}
