<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('persons', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('first_name', 255);
			$table->string('middle_name', 255);
			$table->string('last_name', 255);
			$table->string('nick_name', 255);
			$table->string('full_name', 255);
			$table->string('prefix_title', 255);
			$table->string('suffix_title', 255);
			$table->string('place_of_birth', 255);
			$table->date('date_of_birth');
			$table->enum('gender', ['male', 'female']);
			$table->string('username', 255);
			$table->string('password', 255);
			$table->text('avatar');
			$table->timestamps();
			$table->softDeletes();

			$table->index(['first_name', 'full_name']);
			$table->index(['gender']);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('persons');
	}

}
