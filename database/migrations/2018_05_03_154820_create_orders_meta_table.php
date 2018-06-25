<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrdersMetaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('orders_meta', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('order_id')->unsigned();
			$table->string('type')->default('null');
			$table->string('key');
			$table->text('value', 65535)->nullable();
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
		Schema::drop('orders_meta');
	}

}
