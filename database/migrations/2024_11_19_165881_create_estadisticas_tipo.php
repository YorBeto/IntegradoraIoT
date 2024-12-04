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

        DB::table('estadistica_tipos')->insert([
            [
                'nombre' => 'Aciertos',
                'tipo_dato' => 'INT',
                'id_juego'=> 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Errores',
                'tipo_dato' => 'INT',
                'id_juego'=> 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Duración total',
                'tipo_dato' => 'TIME',
                'id_juego'=> 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Luces Rojas Superadas',
                'tipo_dato' => 'INT',
                'id_juego'=> 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Aciertos',
                'tipo_dato' => 'INT',
                'id_juego'=> 3,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Duración total',
                'tipo_dato' => 'TIME',
                'id_juego'=> 3,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
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
