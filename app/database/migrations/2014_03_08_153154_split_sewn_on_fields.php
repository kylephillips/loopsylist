<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SplitSewnOnFields extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('dolls', function(Blueprint $table)
		{
			$table->dropColumn('sewn_on');
			$table->string('sewn_on_month');
			$table->string('sewn_on_year');
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
			$table->timestamp('sewn_on');
			$table->dropColumn('sewn_on_month');
			$table->dropColumn('sewn_on_year');
		});
	}

}