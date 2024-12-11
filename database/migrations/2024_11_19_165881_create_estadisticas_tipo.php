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
        Schema::create('estadisticas_tipos', function (Blueprint $table) {
            $table->id('id_estadistica');
            $table->string('nombre', 50);
            $table->enum('tipo_dato', ['INT', 'FLOAT', 'STRING', 'TIME']);
            $table->timestamps();
        });

        DB::table('estadisticas_tipos')->insert([
            ['id_estadistica' => 1, 'nombre' => 'Puntuacion', 'tipo_dato' => 'INT', 'created_at' => now(), 'updated_at' => now()],
            ['id_estadistica' => 2, 'nombre' => 'Tiempo', 'tipo_dato' => 'TIME', 'created_at' => now(), 'updated_at' => now()],
            ['id_estadistica' => 3, 'nombre' => 'Mejor puntuacion', 'tipo_dato' => 'INT', 'created_at' => now(), 'updated_at' => now()],
            ['id_estadistica' => 4, 'nombre' => 'Luces Rojas superadas', 'tipo_dato' => 'INT', 'created_at' => now(), 'updated_at' => now()],
            ['id_estadistica' => 5, 'nombre' => 'Mejor tiempo', 'tipo_dato' => 'TIME', 'created_at' => now(), 'updated_at' => now()]
        ]);
    }
    

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('estadisticas_tipos');
    }
};
