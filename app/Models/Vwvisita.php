<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vwvisita extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'cliente', 'cliente_id', 'fechaingreso', 'horaingreso', 'fechasalida', 'horasalida', 'visitante', 'docidentidad', 'residente', 'nrovivienda', 'motivo', 'otros', 'estado', 'imgs'];
}
