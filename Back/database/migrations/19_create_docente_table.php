<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('docente', function (Blueprint $table) {
            $table->id('id_docente');
            $table->integer('DNI')->nullable();
            $table->string('nombre', 20)->nullable();
            $table->string('apellido', 20)->nullable();
            $table->string('email', 30)->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('genero', 10)->nullable();
            $table->date('fecha_nac')->nullable();
            $table->string('direccion', 30)->nullable();
            $table->unsignedBigInteger('id_localidad')->nullable();
            $table->boolean('estado')->default(false);
            $table->string('ExpProfecional', 255)->nullable();
            $table->integer('DispHoraria')->nullable();
            $table->string('estudios', 255)->nullable();
            $table->timestamps();

            $table->foreign('id_localidad')->references('id_localidad')->on('localidad');
        });
    }

    public function down()
    {
        Schema::dropIfExists('docente');
    }
};
