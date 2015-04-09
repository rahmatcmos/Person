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
			$table->integer('organisation_id')->unsigned()->index();
			$table->string('first_name', 255);
			$table->string('middle_name', 255);
			$table->string('last_name', 255);
			$table->string('nick_name', 255);
			$table->string('prefix_title', 255);
			$table->string('suffix_title', 255);
			$table->string('place_of_birth', 255);
			$table->date('date_of_birth');
			$table->enum('gender', ['male', 'female']);
			$table->enum('marital_status', ['single', 'married','divorced', 'widowed']);
			$table->string('nationality', 255);
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
		Schema::drop('persons');
	}

}
