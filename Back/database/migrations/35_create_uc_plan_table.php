<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('uc_plan', function (Blueprint $table) {
            $table->unsignedBigInteger('id_uc');
            $table->unsignedBigInteger('id_plan');
            $table->timestamps();

            $table->primary(['id_uc', 'id_plan']);
            $table->foreign('id_uc')->references('id_uc')->on('unidad_curricular')->onDelete('cascade');
            $table->foreign('id_plan')->references('id_plan')->on('plan_estudio')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('uc_plan');
    }
};