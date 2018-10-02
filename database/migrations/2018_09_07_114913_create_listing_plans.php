<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateListingPlans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('listing_plans', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('group')->nullable();
            $table->text('description')->nullable();
            $table->decimal('price', 11, 2)->nullable();
            $table->integer('credits')->nullable();
            $table->integer('duration_units')->nullable()->default(1);
            $table->enum('duration_period', ['hour', 'day', 'week', 'month', 'year'])->default('week');
            $table->integer('images')->nullable()->default(1);
            $table->boolean('spotlight')->nullable()->default(1);
            $table->boolean('priority')->nullable()->default(1);
            $table->boolean('bold')->nullable()->default(1);
            $table->integer('category_id')->nullable()->default(1);
            $table->integer('min_price')->nullable()->default(1);
            $table->integer('max_price')->nullable()->default(1);
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::drop('listing_plans');
    }
}
