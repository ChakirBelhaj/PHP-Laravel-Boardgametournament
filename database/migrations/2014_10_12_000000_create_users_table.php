<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('firstname');
            $table->string('insertion')->nullable();
            $table->string('lastname');
            $table->string('city')->nullable();
            $table->string('username',99)->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->binary('image')->nullable();
            $table->integer('wins')->default(0);
            $table->integer('losses')->default(0);
            $table->integer('points')->default(0);
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
        });
        //mediumblob up to 16 mb
        DB::statement("ALTER TABLE users MODIFY image MEDIUMBLOB");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
