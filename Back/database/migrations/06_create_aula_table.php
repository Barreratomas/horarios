<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('aula', function (Blueprint $table) {
            $table->id('id_aula');
            $table->string('nombre', 50)->nullable();
            $table->integer('capacidad')->nullable();
            $table->string('tipo_aula', 50)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('aula');
    }
};
