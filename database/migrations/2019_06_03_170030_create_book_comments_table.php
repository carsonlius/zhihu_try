<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('book_comments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('content', 128)->comment('短评内容');
            $table->integer('touch_nums')->default(1)->comment('被发表的次数');
            $table->integer('book_id')->comment('book id');
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
        Schema::dropIfExists('book_comments');
    }
}
