<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateListingServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('listing_services', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('listing_id')->nullable();
            $table->string('name')->nullable();
            $table->integer('duration')->nullable()->default(0);
            $table->decimal('price', 11, 2)->nullable()->default(0.00);
            $table->integer('stock')->nullable()->default(0);
            $table->integer('position')->nullable()->default(0);
            $table->text('meta')->nullable();
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
        Schema::dropIfExists('listing_services');
    }
}
