<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePricingModelsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pricing_models', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('name')->nullable();
			$table->string('seller_label')->nullable();
			$table->string('widget')->nullable();
			$table->string('unit_name')->nullable();
			$table->string('duration_name')->nullable();
			$table->string('price_display')->nullable()->default('unit')->comment('unit/duration');
			$table->string('breakdown_display')->nullable()->default('unit')->comment('unit/duration');
			$table->string('quantity_label')->nullable()->default('quantity');
			$table->boolean('can_accept_payments')->nullable()->default(0);
			$table->boolean('can_add_variants')->nullable()->default(0);
			$table->boolean('can_add_shipping')->nullable()->default(0);
			$table->boolean('can_add_pricing')->nullable()->default(0);
			$table->boolean('can_add_additional_pricing')->nullable()->default(0);
			$table->boolean('requires_shipping_address')->nullable()->default(0);
			$table->boolean('requires_billing_address')->nullable()->default(0);
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
		Schema::drop('pricing_models');
	}

}
