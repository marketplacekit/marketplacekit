<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrdersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('orders', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('listing_id')->nullable();
			$table->integer('seller_id')->nullable();
			$table->integer('user_id')->nullable();
			$table->string('status')->nullable()->default('open');
			$table->decimal('amount', 11, 2)->nullable();
			$table->decimal('service_fee', 11, 2)->nullable()->default(0.00);
			$table->string('currency')->nullable();
			$table->integer('units')->nullable();
			$table->integer('payment_gateway_id')->nullable();
			$table->string('processor')->nullable();
			$table->string('authorization_id')->nullable();
			$table->string('capture_id')->nullable();
			$table->string('refund_id')->nullable();
			$table->string('reference')->nullable();
			$table->text('token')->nullable();
			$table->text('listing_options')->nullable();
			$table->text('choices')->nullable();
			$table->text('customer_details')->nullable();
			$table->dateTime('accepted_at')->nullable();
			$table->dateTime('declined_at')->nullable();
			$table->softDeletes();
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
		Schema::drop('orders');
	}

}
