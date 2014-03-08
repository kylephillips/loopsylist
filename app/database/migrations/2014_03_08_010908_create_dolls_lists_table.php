<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDollsListsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('dolls_lists', function(Blueprint $table)
		{
			$table->increments('id')->unsigned();
			$table->integer('doll_id')->unsigned();
			$table->integer('list_id')->unsigned();
			$table->integer('order')->unsigned();
			$table->string('status');
			$table->timestamps();
			$table->foreign('doll_id')->references('id')->on('dolls');
			$table->foreign('list_id')->references('id')->on('lists');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('dolls_lists');
	}

}