<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePathTree extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('path_tree', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('path')->comment('路径');
            $table->string('name', 40)->comment('行政区划的名字');
            $table->string('code', 6)->comment('行政区划代码');
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
        Schema::dropIfExists('path_tree');
    }
}
