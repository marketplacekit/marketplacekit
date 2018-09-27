<?php

use Illuminate\Database\Migrations\Migration;
#use Grimzy\LaravelMysqlSpatial\Schema\Blueprint;
use Illuminate\Database\Schema\Blueprint;

class CreateListingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('listings', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->string('key')->nullable();
			$table->integer('user_id')->nullable();
			$table->integer('category_id')->nullable();
			$table->string('pricing_model_id')->nullable();
			$table->string('title')->nullable();
			$table->string('blurb', 191)->nullable();
			$table->string('photo')->nullable();
			$table->integer('quantity')->nullable()->default(0);
			$table->integer('stock')->nullable()->default(1);
			$table->text('photos', 65535)->nullable();
			$table->text('description')->nullable();
			$table->dateTime('spotlight')->nullable();
			$table->boolean('staff_pick')->nullable();
			$table->integer('views_count')->nullable();
			$table->string('unit')->nullable();
			$table->integer('min_units')->nullable()->default(1);
			$table->integer('max_units')->nullable();
			$table->integer('min_duration')->nullable();
			$table->integer('max_duration')->nullable();
			$table->integer('session_duration')->nullable();
			$table->string('pricing_models')->nullable();
			$table->decimal('price', 11, 2)->nullable();
			$table->string('currency')->nullable();
            $table->point('location')->nullable();
			$table->decimal('lat', 10, 8)->nullable();
			$table->decimal('lng', 11, 8)->nullable();
			$table->text('meta')->nullable();
			$table->string('city')->nullable();
			$table->string('country')->nullable();
			$table->string('seller_type')->nullable();
			$table->text('variant_options')->nullable();
			$table->string('vendor')->nullable();
			$table->text('timeslots')->nullable();
			$table->text('tags')->nullable();
			$table->text('tags_string', 65535)->nullable();
			$table->string('units_in_product_display')->nullable();
			$table->string('price_per_unit_display')->nullable();
			$table->string('locale', 2)->nullable()->default('en');
			$table->boolean('is_private')->nullable()->default(0);
			$table->boolean('is_published')->nullable()->default(0);
			$table->dateTime('is_admin_verified')->nullable();
			$table->dateTime('is_disabled')->nullable();
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
		Schema::drop('listings');
	}

}
