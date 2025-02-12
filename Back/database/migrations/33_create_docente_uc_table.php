<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('docente_uc', function (Blueprint $table) {
            $table->unsignedBigInteger('id_docente');
            $table->unsignedBigInteger('id_uc');
            $table->timestamps();

            $table->primary(['id_docente', 'id_uc']);
            $table->foreign('id_docente')->references('id_docente')->on('docente')->onDelete('cascade');
            $table->foreign('id_uc')->references('id_uc')->on('unidad_curricular')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('docente_uc');
    }
};
