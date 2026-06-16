<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Intervalo extends Model
{
    use HasFactory;

    protected $fillable = [
        'designacione_id',
        'hora'
    ];

    // Relación con Designacione
    public function designacione()
    {
        return $this->belongsTo(Designacione::class);
    }

    // Relación con Hombrevivos
    public function hombrevivos()
    {
        return $this->hasMany(Hombrevivo::class);
    }

    // Scope para obtener hombrevivos de hoy
    public function scopeConHombrevivoHoy($query, $fecha = null)
    {
        $fecha = $fecha ?? date('Y-m-d');

        return $query->with(['hombrevivos' => function($q) use ($fecha) {
            $q->where('fecha', $fecha)
              ->where('status', true);
        }]);
    }
}
