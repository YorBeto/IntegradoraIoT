<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Juego extends Model
{
    use HasFactory;

    protected $table = 'juegos';

    protected $fillable = ['nombre', 'descripcion'];

    public function partidas()
    {
        return $this->hasMany(Partida::class, 'id_juego');
    }

    public function estadisticasTipos()
    {
        return $this->hasMany(EstadisticaTipo::class, 'id_juego');
    }
}
