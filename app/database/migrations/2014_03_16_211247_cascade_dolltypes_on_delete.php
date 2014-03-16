<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CascadeDolltypesOnDelete extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('dolls_dolltypes', function(Blueprint $table)
		{
			$table->dropForeign('dolls_dolltypes_doll_id_foreign');
			$table->foreign('doll_id')->references('id')->on('dolls')->onDelete('CASCADE')->onUpdate('CASCADE');
			$table->dropForeign('dolls_dolltypes_dolltype_id_foreign');
			$table->foreign('doll_type_id')->references('id')->on('dolltypes')->onDelete('CASCADE')->onUpdate('CASCADE');
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
			//
		});
	}

}