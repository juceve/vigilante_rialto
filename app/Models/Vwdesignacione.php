<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vwdesignacione extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'empleado_id', 'empleado', 'turno_id', 'turno', 'horainicio', 'horafin', 'cliente_id', 'cliente', 'fechaInicio', 'fechaFin', 'intervalo_hv', 'observaciones', 'tipo', 'estado'];

    public function designaciondias()
    {
        return $this->hasMany('App\Models\Designaciondia', 'designacione_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function datosempleado()
    {
        return $this->hasOne('App\Models\Empleado', 'id', 'empleado_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function datosturno()
    {
        return $this->hasOne('App\Models\Turno', 'id', 'turno_id');
    }

    public function regrondas()
    {
        return $this->hasMany('App\Models\Regronda', 'designacione_id', 'id');
    }

    public function marcaciones()
    {
        return $this->hasMany('App\Models\Marcacione', 'designacione_id', 'id');
    }

    public function intervalos()
    {
        return $this->hasMany('App\Models\Intervalo', 'designacione_id', 'id');
    }

    public function novedades()
    {
        return $this->hasMany('App\Models\Novedade', 'designacione_id', 'id');
    }
}
