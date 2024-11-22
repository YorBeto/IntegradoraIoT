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
        Schema::create('estadistica_tipos', function (Blueprint $table) {
            $table->id('id_estadistica');
            $table->string('nombre', 50);
            $table->enum('tipo_dato', ['INT', 'FLOAT', 'STRING', 'TIME']);
            $table->unsignedBigInteger('id_juego');
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
