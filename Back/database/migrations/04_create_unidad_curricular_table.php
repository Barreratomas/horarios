<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('unidad_curricular', function (Blueprint $table) {
            $table->id('id_uc');
            $table->string('unidad_curricular', 60)->nullable();
            $table->string('tipo', 20)->nullable();
            $table->integer('horas_sem')->nullable();
            $table->integer('horas_anual')->nullable();
            $table->string('formato', 20)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('unidad_curricular');
    }
};

