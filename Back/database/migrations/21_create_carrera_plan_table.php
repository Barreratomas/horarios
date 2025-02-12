<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('carrera_plan', function (Blueprint $table) {
            $table->unsignedBigInteger('id_plan');
            $table->unsignedBigInteger('id_carrera');
            $table->timestamps();

            $table->primary(['id_plan', 'id_carrera']);
            $table->foreign('id_plan')->references('id_plan')->on('plan_estudio');
            $table->foreign('id_carrera')->references('id_carrera')->on('carrera');
        });
    }

    public function down()
    {
        Schema::dropIfExists('carrera_plan');
    }
};

