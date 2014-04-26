<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CorrectDollsListsForeign extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('dolls_lists', function(Blueprint $table)
		{
			$table->dropForeign('dolls_lists_list_id_foreign');
			$table->foreign('list_id')->references('id')->on('lists')->onDelete('CASCADE')->onUpdate('CASCADE');;
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('dolls_lists', function(Blueprint $table)
		{
			//
		});
	}

}