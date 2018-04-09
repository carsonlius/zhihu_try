<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTopicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('topics', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50)->comment('话题名称');
            $table->string('image')->nullable()->comment('话题的介绍图片');
            $table->text('brief')->nullable()->comment('话题摘要');
            $table->unsignedInteger('questions_count')->nullable()->default(0)->comment('话题下面问题的个数');
            $table->unsignedInteger('essences_count')->nullable()->default(0)->comment('话题下面的精华问题的个数');
            $table->unsignedInteger('followers_count')->nullable()->default(1)->comment('话题下面关注的人的个数');
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
        Schema::dropIfExists('topics');
    }
}
