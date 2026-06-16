<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Cldotacion
 *
 * @property $id
 * @property $cliente_id
 * @property $fecha
 * @property $status
 * @property $created_at
 * @property $updated_at
 *
 * @property Cldotaciondetalle[] $cldotaciondetalles
 * @property Cliente $cliente
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Cldotacion extends Model
{
    
    static $rules = [
		'fecha' => 'required',
		'status' => 'required',
		'responsable_entrega' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['cliente_id','fecha','status','responsable_entrega'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cldotaciondetalles()
    {
        return $this->hasMany('App\Models\Cldotaciondetalle', 'cldotacion_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function cliente()
    {
        return $this->hasOne('App\Models\Cliente', 'id', 'cliente_id');
    }
    

}
