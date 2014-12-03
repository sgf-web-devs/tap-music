<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddUserDetailsToSongsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('songs', function(Blueprint $table)
		{
            $table->string('userID')->nullable();
            $table->string('userName')->nullable();
            $table->string('userImage')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('songs', function(Blueprint $table)
		{
            $table->dropColumn('userID');
            $table->dropColumn('userName');
            $table->dropColumn('userImage');
		});
	}

}
