<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('grado', function (Blueprint $table) {
            $table->id('id_grado');
            $table->integer('grado')->nullable();
            $table->integer('division')->nullable();
            $table->string('detalle', 70)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('grado');
    }
};
