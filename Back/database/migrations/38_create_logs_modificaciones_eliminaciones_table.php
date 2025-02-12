<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('logs_modificaciones_eliminaciones', function (Blueprint $table) {
            $table->id('id_log');
            $table->string('accion', 255);
            $table->string('usuario', 255);
            $table->timestamp('fecha_accion')->useCurrent();
            $table->text('detalles')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('logs_modificaciones_eliminaciones');
    }
};
