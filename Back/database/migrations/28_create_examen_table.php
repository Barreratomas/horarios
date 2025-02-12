<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('examen', function (Blueprint $table) {
            $table->id('id_examen');
            $table->date('fecha')->nullable();
            $table->time('hora')->nullable();
            $table->unsignedBigInteger('id_aula')->nullable();
            $table->unsignedBigInteger('id_docente')->nullable();
            $table->unsignedBigInteger('id_uc')->nullable();
            $table->timestamps();

            $table->foreign('id_aula')->references('id_aula')->on('aula')->onDelete('cascade');
            $table->foreign('id_docente')->references('id_docente')->on('docente')->onDelete('cascade');
            $table->foreign('id_uc')->references('id_uc')->on('unidad_curricular')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('examen');
    }
};

