<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('asistencia', function (Blueprint $table) {
            $table->id('id_asistencia');
            $table->unsignedBigInteger('id_alumno')->nullable();
            $table->unsignedBigInteger('id_uc')->nullable();
            $table->string('asistencia', 50)->nullable();
            $table->dateTime('fecha')->nullable();
            $table->timestamps();

            $table->foreign('id_alumno')->references('id_alumno')->on('alumno')->onDelete('cascade');
            $table->foreign('id_uc')->references('id_uc')->on('unidad_curricular')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('asistencia');
    }
};
