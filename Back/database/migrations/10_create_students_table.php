<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->default('');
            $table->string('dni', 255)->default('');
            $table->string('email', 255)->default('');
            $table->string('password', 60)->default('');
            $table->string('career', 255)->default('');
            $table->string('profile_photo', 255)->nullable();
            $table->boolean('approved')->default(false);
            $table->string('reset_password_token', 255)->nullable();
            $table->boolean('reset_password_used')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('students');
    }
};

