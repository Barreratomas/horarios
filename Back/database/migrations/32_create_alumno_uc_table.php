<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('alumno_uc', function (Blueprint $table) {
            $table->unsignedBigInteger('id_alumno');
            $table->unsignedBigInteger('id_uc');
            $table->timestamps();

            $table->primary(['id_alumno', 'id_uc']);
            $table->foreign('id_alumno')->references('id_alumno')->on('alumno')->onDelete('cascade');
            $table->foreign('id_uc')->references('id_uc')->on('unidad_curricular')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('alumno_uc');
    }
};