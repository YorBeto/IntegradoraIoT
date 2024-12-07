<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estadisticas_generales', function (Blueprint $table) {
            $table->unsignedBigInteger('id_kid');
            $table->unsignedBigInteger('id_juego');
            $table->time('total_tiempo_jugado')->default('00:00:00');
            $table->integer('numero_partidas')->default(0);
            $table->primary(['id_kid', 'id_juego']);
            $table->foreign('id_kid')->references('id_kid')->on('kids')->onDelete('cascade');
            $table->foreign('id_juego')->references('id_juego')->on('juegos')->onDelete('cascade');
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('estadisticas_generales');
    }
};
