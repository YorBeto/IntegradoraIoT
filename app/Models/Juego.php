<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Juego extends Model
{
    use HasFactory;

    protected $table = 'juegos';
    protected $primaryKey = 'id_juego'; // Indica la columna primaria
    protected $fillable = ['id_juego','nombre', 'descripcion','imagen'];

    public function partidas()
    {
        return $this->hasMany(Partida::class, 'id_juego');
    }

    public function estadisticasTipos()
    {
        return $this->hasMany(EstadisticaTipo::class, 'id_juego');
    }
    public function getImagenUrlAttribute()
    {
        return Storage::disk('s3')->url($this->imagen);
    }
}
