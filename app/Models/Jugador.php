<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jugador extends Model
{
    use HasFactory;
    protected $table = "Jugadores";

    protected $fillable = [
        'NombreCompleto',
        'Edad',
        'Numero',
        'Posicion',
        'Id_Equipo',
        'Id_Liga',
        'NumCampeonatos',
        'Estatus',
        'Correo',
        'Estatura',
        'Foto',
        'Password'

    ];

    public $timestamps = true; // (esto ya viene por defecto)
}
