<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportedListingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reported_listings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('listing_id');
            $table->integer('user_id');
            $table->string('reason')->nullable();
            $table->text('notes')->nullable();
            $table->integer('moderator_id')->nullable();
            $table->text('moderator_message')->nullable();
            $table->boolean('active')->default(true)->nullable();
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
        Schema::dropIfExists('reported_listings');
    }
}
