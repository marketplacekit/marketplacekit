<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMetaAttributesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('meta_attributes', function(Blueprint $table)
		{
			$table->increments('meta_id');
			$table->string('meta_key')->index();
			$table->text('meta_value');
			$table->string('meta_type')->default('string');
			$table->string('meta_group')->nullable();
			$table->string('metable_type');
			$table->bigInteger('metable_id')->unsigned();
			$table->index(['metable_type','metable_id']);
		});
			
			
					
			\Schema::getConnection()->statement(
                'create index meta_attributes_index_value on meta_attributes (meta_key, meta_value(20))'
            );
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('meta_attributes');
	}

}
