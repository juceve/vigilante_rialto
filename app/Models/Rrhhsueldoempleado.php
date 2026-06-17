<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Rrhhsueldoempleado
 *
 * @property $id
 * @property $rrhhsueldo_id
 * @property $empleado_id
 * @property $rrhhcontrato_id
 * @property $nombreempleado
 * @property $total_permisos
 * @property $total_adelantos
 * @property $total_bonos
 * @property $total_descuentos
 * @property $total_ctrlasistencias
 * @property $salario_mes
 * @property $liquido_pagable
 * @property $created_at
 * @property $updated_at
 *
 * @property Empleado $empleado
 * @property Rrhhcontrato $rrhhcontrato
 * @property Rrhhsueldo $rrhhsueldo
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Rrhhsueldoempleado extends Model
{

    static $rules = [
		'rrhhsueldo_id' => 'required',
		'total_permisos' => 'required',
		'total_adelantos' => 'required',
		'total_bonos' => 'required',
		'total_descuentos' => 'required',
		'total_ctrlasistencias' => 'required',
		'total_marcaciones_incompletas' => 'required',
		'salario_mes' => 'required',
		'liquido_pagable' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['rrhhsueldo_id','empleado_id','rrhhcontrato_id','nombreempleado','total_permisos','total_adelantos','total_bonos', 'ids_bonos', 'total_descuentos','total_ctrlasistencias','total_marcaciones_incompletas','salario_mes','liquido_pagable'];


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
    public function rrhhsueldo()
    {
        return $this->hasOne('App\Models\Rrhhsueldo', 'id', 'rrhhsueldo_id');
    }


}
