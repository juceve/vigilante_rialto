<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Citeinforme
 *
 * @property $id
 * @property $correlativo
 * @property $gestion
 * @property $cite
 * @property $fecha
 * @property $fechaliteral
 * @property $objeto
 * @property $cliente
 * @property $representante
 * @property $referencia
 * @property $puntos
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Citeinforme extends Model
{
    
    static $rules = [
		'correlativo' => 'required',
		'gestion' => 'required',
		'cite' => 'required',
		'fecha' => 'required',
		'fechaliteral' => 'required',
		'objeto' => 'required',
		'cliente' => 'required',
		'representante' => 'required',
		'referencia' => 'required',
		'puntos' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['correlativo','gestion','cite','fecha','fechaliteral','objeto','cliente','cliente_id','representante','referencia','puntos','estado'];


	public function cliente_data()
    {
        return $this->hasOne('App\Models\Cliente', 'id', 'cliente_id');
    }
}
