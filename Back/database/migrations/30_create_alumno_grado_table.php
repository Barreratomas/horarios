<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('alumno_grado', function (Blueprint $table) {
            $table->unsignedBigInteger('id_alumno');
            $table->unsignedBigInteger('id_carrera_grado');
            $table->timestamps();

            $table->primary(['id_alumno', 'id_carrera_grado']);
            $table->foreign('id_alumno')->references('id_alumno')->on('alumno')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('alumno_grado');
    }
};