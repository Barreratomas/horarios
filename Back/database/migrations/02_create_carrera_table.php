<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('carrera', function (Blueprint $table) {
            $table->id('id_carrera');
            $table->string('carrera', 70)->nullable();
            $table->integer('cupo')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('carrera');
    }
};
