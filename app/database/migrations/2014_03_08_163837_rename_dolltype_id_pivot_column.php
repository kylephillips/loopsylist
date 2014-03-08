<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameDolltypeIdPivotColumn extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('dolls_dolltypes', function(Blueprint $table)
		{
			$table->renameColumn('dolltype_id','doll_type_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('dolls_dolltypes', function(Blueprint $table)
		{
			$table->renameColumn('doll_type_id','dolltype_id');
		});
	}

}