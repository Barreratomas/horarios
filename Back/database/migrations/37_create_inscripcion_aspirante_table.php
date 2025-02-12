<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('inscripcion_aspirante', function (Blueprint $table) {
            $table->id('id_inscripcion');
            $table->dateTime('fechahora')->nullable();
            $table->unsignedBigInteger('id_aspirante')->nullable();
            $table->unsignedBigInteger('id_carrera')->nullable();
            $table->unsignedBigInteger('id_grado')->nullable();
            $table->timestamps();

            $table->foreign('id_aspirante')->references('id_aspirante')->on('aspirante')->onDelete('cascade');
            $table->foreign('id_carrera')->references('id_carrera')->on('carrera')->onDelete('cascade');
            $table->foreign('id_grado')->references('id_grado')->on('grado')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('inscripcion_aspirante');
    }
};
