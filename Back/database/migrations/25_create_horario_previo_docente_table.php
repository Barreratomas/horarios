<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('horario_previo_docente', function (Blueprint $table) {
            $table->id('id_h_p_d');
            $table->unsignedBigInteger('id_docente')->nullable();
            $table->string('dia', 50)->nullable();
            $table->time('hora')->nullable();
            $table->timestamps();

            $table->foreign('id_docente')->references('id_docente')->on('docente')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('horario_previo_docente');
    }
};