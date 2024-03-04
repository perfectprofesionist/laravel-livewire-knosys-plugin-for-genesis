<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKnoUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kno_users', function (Blueprint $table) {
            $table->uuid('user_id')->unique();
            $table->text('access_token');
            $table->integer('access_expires_in');
            $table->text('public_token');
            $table->integer('public_access_expires_in');
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
        Schema::dropIfExists('kno_users');
    }
}
