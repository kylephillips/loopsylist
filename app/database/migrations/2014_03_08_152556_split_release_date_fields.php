<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SplitReleaseDateFields extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('dolls', function(Blueprint $table)
		{
			$table->dropColumn('release_date');
			$table->string('release_month');
			$table->string('release_year');
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
			$table->timestamp('release_date');
			$table->dropColumn('release_month');
			$table->dropColumn('release_year');
		});
	}

}