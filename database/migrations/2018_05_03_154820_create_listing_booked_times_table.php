<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateListingBookedTimesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('listing_booked_times', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('listing_id')->nullable();
			$table->dateTime('booked_date')->nullable();
			$table->time('start_time')->nullable();
			$table->integer('quantity')->nullable()->default(0);
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
		Schema::drop('listing_booked_times');
	}

}
