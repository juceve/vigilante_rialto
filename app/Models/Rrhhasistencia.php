<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Rrhhasistencia
 *
 * @property $id
 * @property $empleado_id
 * @property $fecha
 * @property $ingreso
 * @property $salida
 * @property $rrhhestado_id
 * @property $observaciones
 * @property $created_at
 * @property $updated_at
 *
 * @property Empleado $empleado
 * @property Rrhhestado $rrhhestado
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Rrhhasistencia extends Model
{
    
    static $rules = [
		'fecha' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['empleado_id','fecha','ingreso','salida','rrhhestado_id','observaciones'];


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
    public function rrhhestado()
    {
        return $this->hasOne('App\Models\Rrhhestado', 'id', 'rrhhestado_id');
    }
    

}
