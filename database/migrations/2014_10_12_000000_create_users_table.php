<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->string('email', 80);
            $table->string('usu_login', 50);
            $table->string('usu_senha', 250);
            $table->string('usu_status', 1);
            $table->string('usu_nivel', 45);
            $table->string('foto', 200)->nullable();
            $table->date('usu_data_cad');
            $table->date('usu_data_update')->nullable();
            $table->string('senha', 50); // Assuming this is a separate field for password reset or something similar
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
        Schema::dropIfExists('users');
    }
}
