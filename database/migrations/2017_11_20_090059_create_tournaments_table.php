<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTournamentsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('tournaments', function (Blueprint $table) {
      $table->increments('id');
      $table->string('name');
      $table->integer('rounds');
      $table->integer('minplayers');
      $table->integer('maxplayers')->nullable();
      $table->date('startdate');
      $table->date('enddate')->nullable();
      $table->string('streetname')->nullable();
      $table->string('housenumber')->nullable();
      $table->string('zipcode')->nullable();
      $table->string('city')->nullable();
      $table->text('description')->nullable();
      $table->integer('boardgame_id');
      $table->integer('status_id')->nullable();
      $table->integer('user_id');
      $table->softDeletes();
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
    Schema::dropIfExists('tournaments');
  }
}
