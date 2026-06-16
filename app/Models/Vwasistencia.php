<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vwasistencia extends Model
{
    use HasFactory;

    protected $fillable = [
        'id', 'designacione_id', 'fecha', 'ingreso', 'salida', 'latingreso', 'lngingreso', 'latsalida', 'lngsalida', 'turno_id', 'turno',
        'turno_horainicio', 'turno_horafin', 'cliente_id', 'cliente', 'empleado_id', 'empleado','designacione_id'
    ];

    public function designacione()
    {
        return $this->hasOne('App\Models\Designacione', 'id', 'designacione_id');
    }
}
