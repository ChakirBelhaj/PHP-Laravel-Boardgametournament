<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTournamentUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tournament_users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('tournament_id');
            $table->integer('boardgame_id')->default(0);  // Boardgame id needed for search rankings on boardgame. -> use relations!
            $table->integer('round');
            $table->integer('round_group')->default(0);
            $table->string('score')->default(0);
            $table->tinyInteger('win')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tournament_users');
    }
}
