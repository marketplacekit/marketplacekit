<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateListingBookedDatesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('listing_booked_dates', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('listing_id')->nullable();
			$table->date('booked_date')->nullable();
			$table->integer('quantity')->nullable()->default(0);
			$table->integer('available_units')->nullable();
			$table->boolean('is_available')->nullable();
			$table->decimal('price', 11, 2)->nullable();
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
		Schema::drop('listing_booked_dates');
	}

}
