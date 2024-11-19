<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tutor extends Model
{
    use HasFactory;

    protected $table = 'tutor';

    protected $fillable = ['id_persona'];

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'id_persona');
    }

    public function kids()
    {
        return $this->hasMany(Kid::class, 'id_tutor');
    }
}
