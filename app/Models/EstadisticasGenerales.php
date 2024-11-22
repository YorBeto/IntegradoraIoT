<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadisticasGenerales extends Model
{
    use HasFactory;

    protected $table = 'estadisticas_generales';

    protected $fillable = [
        'id_kid',
        'id_juego',
        'tiempo_jugado_total',
        'numero_partidas',
    ];

    public function kid()
    {
        return $this->belongsTo(Kid::class, 'id_kid');
    }

    public function juego()
    {
        return $this->belongsTo(Juego::class, 'id_juego');
    }
}
