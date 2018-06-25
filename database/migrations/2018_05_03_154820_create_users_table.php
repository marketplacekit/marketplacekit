<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 191);
			$table->string('display_name')->nullable();
			$table->string('username')->nullable();
			$table->string('slug')->nullable();
			$table->text('bio', 65535)->nullable();
			$table->string('phone')->nullable();
			$table->string('email', 191)->unique();
			$table->string('avatar', 191)->nullable();
			$table->string('password', 191)->nullable();
			$table->string('remember_token', 100)->nullable();
			$table->char('gender', 1)->nullable();
			$table->string('city')->nullable();
			$table->string('region')->nullable();
			$table->char('country', 3)->nullable();
			$table->string('country_name')->nullable();
			$table->char('locale', 5)->nullable()->default('en');
			$table->integer('unread_messages')->nullable()->default(0);
			$table->boolean('is_admin')->nullable()->default(0);
			$table->string('ip_address')->nullable();
			$table->dateTime('last_login_at')->nullable();
			$table->string('last_login_ip')->nullable();
			$table->dateTime('blocked_at')->nullable();
			$table->timestamps();
			$table->softDeletes();
			$table->boolean('can_accept_payments')->nullable()->default(0);
			$table->boolean('verified')->default(0);
			$table->string('verification_token')->nullable();
			$table->dateTime('banned_at')->nullable();
			$table->string('provider')->nullable();
			$table->string('provider_id')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}
