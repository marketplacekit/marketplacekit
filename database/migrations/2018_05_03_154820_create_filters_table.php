<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFiltersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('filters', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('position')->nullable();
			$table->string('name')->nullable();
			$table->string('field')->nullable();
			$table->string('search_ui')->nullable();
			$table->string('form_input_type')->nullable();
			$table->text('form_input_meta')->nullable();
			$table->boolean('is_category_specific')->nullable()->default(0);
			$table->boolean('is_searchable')->nullable()->default(0);
			$table->boolean('is_hidden')->nullable()->default(0);
			$table->boolean('is_default')->nullable()->default(0);
			$table->text('categories')->nullable();
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
		Schema::drop('filters');
	}

}
