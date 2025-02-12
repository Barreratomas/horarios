<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('horario', function (Blueprint $table) {
            $table->id('id_horario');
            $table->string('dia', 50)->nullable();
            $table->string('modulo_inicio', 10)->nullable();
            $table->string('modulo_fin', 10)->nullable();
            $table->string('modalidad', 50)->nullable();
            $table->unsignedBigInteger('id_disp')->nullable();
            $table->timestamps();

            $table->foreign('id_disp')->references('id_disp')->on('disponibilidad')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('horario');
    }
};

