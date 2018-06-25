<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePageTranslationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('page_translations', function(Blueprint $table)
		{
			$table->increments('id');
			$table->char('locale', 5);
			$table->string('title');
			$table->string('slug');
			$table->text('content', 65535)->nullable();
			$table->string('seo_title')->nullable();
			$table->string('seo_meta_description')->nullable();
			$table->string('seo_meta_keywords')->nullable();
			$table->boolean('visible')->default(1);
			$table->timestamp('published_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
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
		Schema::drop('page_translations');
	}

}
