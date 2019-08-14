<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookClosureRelationshipTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('book_closure_relationship', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ancestor_id')->comment('祖先分类ID');
            $table->integer('descendant_id')->comment('后代分类ID');
            $table->integer('distance')->comment('祖先分类到后代分类的距离');
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
        Schema::dropIfExists('book_closure_relationship');
    }
}
