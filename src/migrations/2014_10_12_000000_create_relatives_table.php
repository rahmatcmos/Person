<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRelativesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('relatives', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('person_id')->unsigned()->index();
			$table->integer('family_id')->unsigned()->index();
			$table->enum('relationship', ['father', 'mother', 'husband', 'wife', 'son', 'daughter']);
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
		Schema::drop('relatives');
	}

}
