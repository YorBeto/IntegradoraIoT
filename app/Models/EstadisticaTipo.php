<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadisticaTipo extends Model
{
    use HasFactory;

    protected $table = 'estadistica_tipo';

    protected $fillable = ['nombre', 'tipo_dato', 'id_juego'];

    public function juego()
    {
        return $this->belongsTo(Juego::class, 'id_juego');
    }
}
