<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Rrhhestado
 *
 * @property $id
 * @property $nombre
 * @property $factor
 * @property $color
 * @property $created_at
 * @property $updated_at
 *
 * @property Rrhhasistencia[] $rrhhasistencias
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Rrhhestado extends Model
{
    
    static $rules = [
		'nombre' => 'required',
		'factor' => 'required',
		'color' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['nombre','nombre_corto','factor','color'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rrhhasistencias()
    {
        return $this->hasMany('App\Models\Rrhhasistencia', 'rrhhestado_id', 'id');
    }
    

}
