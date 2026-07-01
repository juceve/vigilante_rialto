<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Sistemaparametro
 *
 * @property $id
 * @property $tolerancia_ingreso
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Sistemaparametro extends Model
{

    static $rules = [
        'tolerancia_ingreso' => 'required',
        'falta_dia_completo' => 'required|numeric|min:0'
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['tolerancia_ingreso', 'telefono_panico', 'asistencia_sin_salida', 'falta_dia_completo', 'salida_anticipada', 'ingreso_atrasado'];

    public function salidaAntesTiempo()
    {
        return $this->hasOne('App\Models\Rrhhtipodescuento', 'id', 'salida_anticipada');
    }

    public function marcadoAtrasado()
    {
        return $this->hasOne('App\Models\Rrhhtipodescuento', 'id', 'ingreso_atrasado');
    }
}
