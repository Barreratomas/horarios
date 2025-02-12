<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('alumno_plan', function (Blueprint $table) {
            $table->unsignedBigInteger('id_plan');
            $table->unsignedBigInteger('id_alumno');
            $table->timestamps();

            $table->primary(['id_plan', 'id_alumno']);
            $table->foreign('id_plan')->references('id_plan')->on('plan_estudio');
            $table->foreign('id_alumno')->references('id_alumno')->on('alumno');
        });
    }

    public function down()
    {
        Schema::dropIfExists('alumno_plan');
    }
};
