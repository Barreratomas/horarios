<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('carrera_grado', function (Blueprint $table) {
            $table->id('id_carrera_grado');
            $table->unsignedBigInteger('id_carrera');
            $table->integer('capacidad')->nullable();
            $table->unsignedBigInteger('id_grado');
            $table->timestamps();

            $table->foreign('id_carrera')->references('id_carrera')->on('carrera')->onDelete('cascade');
            $table->foreign('id_grado')->references('id_grado')->on('grado')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('carrera_grado');
    }
};