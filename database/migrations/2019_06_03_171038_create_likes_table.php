<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('likes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('slug_id')->comment('对应类型的ID');
            $table->enum('type', ['music', 'movie', 'text', 'book'])->comment('期刊 或者书类型 music 音乐 movie 电影 text 句子 book 书本'); //  music 音乐 movie 电影 text 句子 book 书本
            $table->integer('user_id')->comment('用户id');
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
        Schema::dropIfExists('likes');
    }
}
