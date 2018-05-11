<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMessagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('messages', function(Blueprint $table)
		{
			$table->increments('id');
			$table->text('message', 65535);
			$table->boolean('is_seen')->default(0);
			$table->boolean('deleted_from_sender')->default(0);
			$table->boolean('deleted_from_receiver')->default(0);
			$table->integer('user_id');
			$table->integer('conversation_id');
			$table->text('attachments')->nullable();
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
		Schema::drop('messages');
	}

}
