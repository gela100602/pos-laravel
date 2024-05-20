<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('gender_id');
            $table->string('name');
            $table->string('email', 100)->unique();
            $table->string('username', 50)->unique();
            $table->string('password', 50);
            $table->string('contact_number', 15)->nullable();
            $table->string('user_image', 255)->nullable();
            $table->timestamps();

            $table->foreign('role_id')
                ->references('role_id')
                ->on('roles')
                ->onUpdate('cascade');

            $table->foreign('gender_id')
                ->references('gender_id')
                ->on('genders')
                ->onUpdate('cascade');
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
};