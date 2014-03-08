<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDollsDolltypesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('dolls_dolltypes', function(Blueprint $table)
		{
			$table->increments('id')->unsigned();
			$table->integer('doll_id')->unsigned();
			$table->integer('dolltype_id')->unsigned();
			$table->foreign('doll_id')->references('id')->on('dolls');
			$table->foreign('dolltype_id')->references('id')->on('dolltypes');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('dolls_dolltypes');
	}

}