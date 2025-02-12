<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('correlatividad', function (Blueprint $table) {
            $table->id('id_correlativa');
            $table->unsignedBigInteger('id_uc')->nullable();
            $table->unsignedBigInteger('correlativa')->nullable();
            $table->unsignedBigInteger('id_carrera')->nullable();
            $table->timestamps();

            $table->foreign('id_uc')->references('id_uc')->on('unidad_curricular')->onDelete('cascade');
            $table->foreign('correlativa')->references('id_uc')->on('unidad_curricular')->onDelete('cascade');
            $table->foreign('id_carrera')->references('id_carrera')->on('carrera')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('correlatividad');
    }
};