<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCommentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('comments', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('listing_id')->nullable()->index('comments_commentable_id_commentable_type_index');
			$table->integer('seller_id')->nullable();
			$table->integer('commenter_id')->nullable()->index('comments_commented_id_commented_type_index');
			$table->text('comment');
			$table->boolean('approved')->default(1);
			$table->float('rate', 15, 8)->nullable();
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
		Schema::drop('comments');
	}

}
