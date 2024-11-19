<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kid extends Model
{
    use HasFactory;

    protected $table = 'kid';

    protected $fillable = ['nombre', 'edad', 'foto_perfil', 'id_tutor'];

    public function tutor()
    {
        return $this->belongsTo(Tutor::class, 'id_tutor');
    }

    public function partidas()
    {
        return $this->hasMany(Partida::class, 'id_kid');
    }
}
