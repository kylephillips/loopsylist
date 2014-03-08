<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameSewnOnYear extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('dolls', function(Blueprint $table)
		{
			$table->renameColumn('sewn_on_year', 'sewn_on_day');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('dolls', function(Blueprint $table)
		{
			$table->renameColumn('sewn_on_day', 'sewn_on_year');
		});
	}

}