<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Citememorandum
 *
 * @property $id
 * @property $correlativo
 * @property $gestion
 * @property $cite
 * @property $fecha
 * @property $fechaliteral
 * @property $empleado
 * @property $empleado_id
 * @property $cuerpo
 * @property $estado
 * @property $created_at
 * @property $updated_at
 *
 * @property Empleado $empleado
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Citememorandum extends Model
{
    
    static $rules = [
		'correlativo' => 'required',
		'gestion' => 'required',
		'cite' => 'required',
		'fecha' => 'required',
		'fechaliteral' => 'required',
		'empleado' => 'required',
		'cuerpo' => 'required',
		'estado' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['correlativo','gestion','cite','fecha','fechaliteral','empleado','empleado_id','cuerpo','estado'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function empleado()
    {
        return $this->hasOne('App\Models\Empleado', 'id', 'empleado_id');
    }
    

}
