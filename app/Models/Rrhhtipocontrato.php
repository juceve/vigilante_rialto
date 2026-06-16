<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Rrhhtipocontrato
 *
 * @property $id
 * @property $codigo
 * @property $nombre
 * @property $descripcion
 * @property $cantidad_dias
 * @property $horas_dia
 * @property $sueldo_referencial
 * @property $activo
 * @property $created_at
 * @property $updated_at
 *
 * @property Rrhhcontrato[] $rrhhcontratos
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Rrhhtipocontrato extends Model
{
    
    static $rules = [		
		'nombre' => 'required',
		'cantidad_dias' => 'required',
		'horas_dia' => 'required',
		'activo' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['codigo','nombre','descripcion', 'mensualizado','cantidad_dias','horas_dia','sueldo_referencial','activo'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rrhhcontratos()
    {
        return $this->hasMany('App\Models\Rrhhcontrato', 'rrhhtipocontrato_id', 'id');
    }
    

}
