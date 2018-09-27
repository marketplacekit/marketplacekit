<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateListingAdditionalOptionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('listing_additional_options', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('listing_id')->nullable();
			$table->decimal('price', 11, 2)->nullable();
			$table->string('name')->nullable();
			$table->integer('position')->nullable()->default(0);
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
		Schema::drop('listing_additional_options');
	}

}
