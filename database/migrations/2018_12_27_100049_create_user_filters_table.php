<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserFiltersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_filters', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('position')->nullable();
            $table->string('name')->nullable();
            $table->string('field')->nullable();
            $table->string('search_ui')->nullable();
            $table->string('form_input_type')->nullable();
            $table->text('form_input_meta')->nullable();
            $table->boolean('is_searchable')->nullable()->default(0);
            $table->boolean('is_hidden')->nullable()->default(0);
            $table->boolean('is_default')->nullable()->default(0);
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
        Schema::dropIfExists('user_filters');
    }
}
