<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('grado_uc', function (Blueprint $table) {
            $table->unsignedBigInteger('id_uc');
            $table->unsignedBigInteger('id_carrera_grado');
            $table->timestamps();

            $table->primary(['id_uc', 'id_carrera_grado']);
            $table->foreign('id_uc')->references('id_uc')->on('unidad_curricular')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('grado_uc');
    }
};
