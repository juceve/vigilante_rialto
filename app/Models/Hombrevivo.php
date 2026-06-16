<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hombrevivo extends Model
{
    use HasFactory;

    protected $fillable = [
        'intervalo_id',
        'fecha',
        'hora',
        'anotaciones',
        'lat',
        'lng',
        'status'
    ];

    protected $casts = [
        'fecha' => 'date',
        'status' => 'boolean'
    ];

    // Relación con Intervalo
    public function intervalo()
    {
        return $this->belongsTo(Intervalo::class);
    }

    // Scope para filtrar por fecha
    public function scopeFecha($query, $fecha)
    {
        return $query->where('fecha', $fecha);
    }

    // Scope para activos
    public function scopeActivos($query)
    {
        return $query->where('status', true);
    }
}
