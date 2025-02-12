<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cambio_docente', function (Blueprint $table) {
            $table->id('id_cambio');
            $table->unsignedBigInteger('id_docente_anterior')->nullable();
            $table->unsignedBigInteger('id_docente_nuevo')->nullable();
            $table->timestamps();

            $table->foreign('id_docente_anterior')->references('id_docente')->on('docente')->onDelete('cascade');
            $table->foreign('id_docente_nuevo')->references('id_docente')->on('docente')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('cambio_docente');
    }
};