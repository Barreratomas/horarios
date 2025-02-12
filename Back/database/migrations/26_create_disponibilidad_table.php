<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('disponibilidad', function (Blueprint $table) {
            $table->id('id_disp');
            $table->unsignedBigInteger('id_uc')->nullable();
            $table->unsignedBigInteger('id_docente')->nullable();
            $table->unsignedBigInteger('id_h_p_d')->nullable();
            $table->unsignedBigInteger('id_aula')->nullable();
            $table->string('dia', 50)->nullable();
            $table->string('modulo_inicio', 10)->nullable();
            $table->string('modulo_fin', 10)->nullable();
            $table->unsignedBigInteger('id_carrera_grado');
            $table->timestamps();

            $table->foreign('id_uc')->references('id_uc')->on('unidad_curricular')->onDelete('cascade');
            $table->foreign('id_docente')->references('id_docente')->on('docente')->onDelete('cascade');
            $table->foreign('id_h_p_d')->references('id_h_p_d')->on('horario_previo_docente')->onDelete('cascade');
            $table->foreign('id_aula')->references('id_aula')->on('aula')->onDelete('cascade');
            $table->foreign('id_carrera_grado')->references('id_carrera_grado')->on('carrera_grado')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('disponibilidad');
    }
};
