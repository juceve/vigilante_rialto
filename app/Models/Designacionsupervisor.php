<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Designacionsupervisor
 *
 * @property $id
 * @property $empleado_id
 * @property $fechaInicio
 * @property $fechaFin
 * @property $observaciones
 * @property $estado
 * @property $created_at
 * @property $updated_at
 *
 * @property Designacionsupervisorcliente[] $designacionsupervisorclientes
 * @property Empleado $empleado
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Designacionsupervisor extends Model
{
    
    static $rules = [
		'fechaInicio' => 'required',
		'estado' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['empleado_id','turnoguardia_id','fechaInicio','fechaFin','observaciones','estado'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function designacionsupervisorclientes()
    {
        return $this->hasMany('App\Models\Designacionsupervisorcliente', 'designacionsupervisor_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function empleado()
    {
        return $this->hasOne('App\Models\Empleado', 'id', 'empleado_id');
    }
    
    public function inspecccions(){
        return $this->hasMany(Inspeccion::class,'designacionsupervisor_id','id');
    }

    public function turnoguardia()
    {
        return $this->hasOne(Turnoguardia::class, 'id', 'turnoguardia_id');
    }

}
