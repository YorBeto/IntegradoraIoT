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
        Schema::create('estadistica_partidas', function (Blueprint $table) {
            $table->unsignedBigInteger('id_partida');
            $table->unsignedBigInteger('id_estadistica_1');
            $table->string('valor_1', 255);
            $table->unsignedBigInteger('id_estadistica_2');
            $table->string('valor_2', 255);
            $table->primary(['id_partida', 'id_estadistica_1', 'id_estadistica_2']);
            $table->foreign('id_partida')->references('id_partida')->on('partidas')->onDelete('cascade');
            $table->foreign('id_estadistica_1')->references('id_estadistica')->on('estadistica_tipos');
            $table->foreign('id_estadistica_2')->references('id_estadistica')->on('estadistica_tipos');
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
