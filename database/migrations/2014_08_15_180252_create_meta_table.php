<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMetaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meta', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('metable_id')->unsigned();
            $table->string('metable_type', 255);
            $table->string('key', 128);
            $table->text('value');

            $table->index('metable_id');
            $table->index('key');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('meta');
    }
}
