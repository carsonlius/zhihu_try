<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('from_user_id')->comment('发起私信的ID');
            $table->unsignedInteger('to_user_id')->comment('接收私信的ID');
            $table->text('body')->comment('私信的内容');
            $table->string('is_read', 8)->default('F')->comment('是否已经被用户阅读, 默认是否');
            $table->timestamp('read_at')->nullable()->comment('用户读取本条message的时间');
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
        Schema::dropIfExists('messages');
    }
}
