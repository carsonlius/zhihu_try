<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePeriodicalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('periodicals', function (Blueprint $table) {
            $table->increments('id');
            $table->string('month', 6)->comment('期刊创建的月份');
            $table->string('img')->comment('封面');
            $table->text('des')->comment('期刊描述');
            $table->enum('type', ['music', 'movie', 'text', 'book'])->comment('期刊类型'); //  music 音乐 movie 电影 text 句子 book 书本
            $table->integer('slug_id')->comment('对应类型ID');
            $table->string('title', '128')->comment('期刊标题');
            $table->tinyInteger('sort_weight')->default(0)->comment('排序权重');
            $table->integer('fav_nums')->default(0)->comment('点赞数量');
            $table->tinyInteger('periodical_index')->comment('第几期');
            $table->enum('published', [1, 2, 3])->comment('1 待发布 2 已发布 3 撤回');
            $table->text('data')->comment('详细的属性');
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
        Schema::dropIfExists('periodicals');
    }
}
