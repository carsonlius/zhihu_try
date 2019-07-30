<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('books', function (Blueprint $table) {
            $table->increments('id');
            $table->string('brief', 128)->comment('简介');
            $table->string('subtitle', 60)->nullable()->comment('附标题');
            $table->string('author', 25)->comment('作者, 外国人名字长');
            $table->string('publisher', 30)->comment('出版社');
            $table->integer('published_at')->comment('出版时间');
            $table->float('price', '4')->comment('价格');
            $table->tinyInteger('layout_type')->comment('1 精装 2平装 3单行本 4合订本');
            $table->tinyInteger('category')->comment('分类 1 科技 2 编程 3 电影 4 音乐 5 动漫');
            $table->string('name', 20)->comment('书名');
            $table->string('img')->comment('封面');
            $table->integer('comment_nums')->default(0)->comment('短评次数');
            $table->integer('fav_nums')->default(0)->comment('点赞数目');
            $table->enum('is_hot', [0, 1])->default(0)->comment('0 不是热门 1热门');
            $table->string('translator', 15)->nullable()->comment('翻译的人');
            $table->string('isbn', 15)->nullable()->comment('国际标准图书编号');
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
        Schema::dropIfExists('books');
    }
}
