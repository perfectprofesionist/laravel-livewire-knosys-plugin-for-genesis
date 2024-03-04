<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGenUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gen_users', function (Blueprint $table) {
            $table->uuid('user_id')->unique();
            $table->string('auth_code');
            $table->string('name');
            $table->string('email');
            $table->string('access_token');
            $table->integer('expires_in');
            $table->string('refresh_token');
            $table->timestamps();
            $table->primary('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gen_users');
    }
}
