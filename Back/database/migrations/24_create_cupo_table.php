<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cupo', function (Blueprint $table) {
            $table->id('id_cupo');
            $table->unsignedBigInteger('id_carrera')->nullable();
            $table->year('ano_lectivo')->nullable();
            $table->integer('cupos_disp')->default(0);
            $table->timestamps();

            $table->foreign('id_carrera')->references('id_carrera')->on('carrera')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('cupo');
    }
};

