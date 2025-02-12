<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('alumno_carrera', function (Blueprint $table) {
            $table->unsignedBigInteger('id_alumno');
            $table->unsignedBigInteger('id_carrera');
            $table->timestamps();

            $table->primary(['id_alumno', 'id_carrera']);
            $table->foreign('id_alumno')->references('id_alumno')->on('alumno')->onDelete('cascade');
            $table->foreign('id_carrera')->references('id_carrera')->on('carrera')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('alumno_carrera');
    }
};
