<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Rrhhdescuento
 *
 * @property $id
 * @property $rrhhcontrato_id
 * @property $fecha
 * @property $rrhhtipodescuento_id
 * @property $empleado_id
 * @property $cantidad
 * @property $monto
 * @property $estado
 * @property $created_at
 * @property $updated_at
 *
 * @property Empleado $empleado
 * @property Rrhhcontrato $rrhhcontrato
 * @property Rrhhtipodescuento $rrhhtipodescuento
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Rrhhdescuento extends Model
{
    
    static $rules = [
		'fecha' => 'required',
		'cantidad' => 'required',
		'monto' => 'required',
		'estado' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['rrhhcontrato_id','fecha','rrhhtipodescuento_id','empleado_id','cantidad','monto','estado'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function empleado()
    {
        return $this->hasOne('App\Models\Empleado', 'id', 'empleado_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function rrhhcontrato()
    {
        return $this->hasOne('App\Models\Rrhhcontrato', 'id', 'rrhhcontrato_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function rrhhtipodescuento()
    {
        return $this->hasOne('App\Models\Rrhhtipodescuento', 'id', 'rrhhtipodescuento_id');
    }
    

}
