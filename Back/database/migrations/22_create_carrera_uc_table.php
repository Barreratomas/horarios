<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('carrera_uc', function (Blueprint $table) {
            $table->unsignedBigInteger('id_carrera');
            $table->unsignedBigInteger('id_uc');
            $table->timestamps();

            $table->primary(['id_carrera', 'id_uc']);
            $table->foreign('id_carrera')->references('id_carrera')->on('carrera')->onDelete('cascade');
            $table->foreign('id_uc')->references('id_uc')->on('unidad_curricular')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('carrera_uc');
    }
};
