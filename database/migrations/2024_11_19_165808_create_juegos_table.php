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
        Schema::create('juegos', function (Blueprint $table) {
            $table->id('id_juego');
            $table->string('nombre', 50);
            $table->text('descripcion');
            $table->string('imagen', 100)->nullable();
            $table->timestamps();
        });

        DB::table('juegos')->insert([
            [
                'nombre' => 'Cazador de colores',
                'descripcion' => 'Un juego donde los niños deben buscar y tocar objetos de un color específico mencionado por el cazador. ¿Podras atrapar todos los colores?.',
                'imagen'=> 'cazador_de_colores.jpg',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Luz verde luz roja',
                'descripcion' => 'Un niño se convierte en el semáforo. Los demás avanzan en luz verde, pero deben detenerse completamente en luz roja. Si se mueven, vuelven al inicio.',
                'imagen'=> 'luz_verde_luz_roja.jpg',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Simon dice',
                'descripcion' => 'Atraviesa los desafios de Simon, pero solo si Simon lo dice. Si no lo dice, no lo hagas. ¿Podras seguir las instrucciones de Simon?',
                'imagen'=> 'simon_dice.jpg',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('juegos');
    }
};
