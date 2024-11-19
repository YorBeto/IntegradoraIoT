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
        Schema::create('kids', function (Blueprint $table) {
            $table->id('id_kid');
            $table->string('nombre', 50);
            $table->integer('edad');
            $table->string('foto_perfil', 255)->nullable();
            $table->unsignedBigInteger('id_tutor');
            $table->foreign('id_tutor')->references('id_tutor')->on('tutores')->onDelete('cascade');
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
        Schema::dropIfExists('kids');
    }
};
