<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Paseingreso
 *
 * @property $id
 * @property $residencia_id
 * @property $nombre
 * @property $cedula
 * @property $fecha_inicio
 * @property $fecha_fin

 * @property $detalles
 * @property $url_foto
 * @property $created_at
 * @property $updated_at
 *
 * @property Residencia $residencia
 * @property Tipopase $tipopase
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Paseingreso extends Model
{

    static $rules = [
		'nombre' => 'required',
		'cedula' => 'required',
		'fecha_inicio' => 'required',
		'fecha_fin' => 'required',
		'detalles' => 'required',
		'usounico' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['residencia_id','nombre','cedula','fecha_inicio','fecha_fin','motivo_id','detalles','usounico','url_foto','estado'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function residencia()
    {
        return $this->hasOne('App\Models\Residencia', 'id', 'residencia_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function motivo()
    {
        return $this->hasOne('App\Models\Motivo', 'id', 'motivo_id');
    }

    public function flujopases()
    {
        return $this->hasMany('App\Models\Flujopase', 'paseingreso_id', 'id');
    }

}
