<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBoardgamesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('boardgames', function (Blueprint $table) {
      $table->increments('id');
      $table->string('name');
      $table->binary('image')->nullable();
      $table->integer('minplayers')->nullable();
      $table->integer('maxplayers');
      $table->integer('playtime');
      $table->boolean('isexpansion')->nullable();
      $table->integer('yearpublished');
      $table->longtext('description')->nullable();
      $table->softDeletes();
      $table->timestamps();
    });
    //mediumblob up to 16 mb
    DB::statement("ALTER TABLE boardgames MODIFY image MEDIUMBLOB");
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('boardgames');
  }
}
