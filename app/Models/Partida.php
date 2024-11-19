<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partida extends Model
{
    use HasFactory;

    protected $table = 'partida';

    protected $fillable = ['id_kid', 'id_juego', 'fecha', 'hora_inicio', 'hora_fin'];

    public function kid()
    {
        return $this->belongsTo(Kid::class, 'id_kid');
    }

    public function juego()
    {
        return $this->belongsTo(Juego::class, 'id_juego');
    }
}
