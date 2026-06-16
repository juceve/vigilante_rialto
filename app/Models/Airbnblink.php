<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Airbnblink
 *
 * @property $id
 * @property $cliente_id
 * @property $solicitante
 * @property $cedula
 * @property $celular
 * @property $link
 * @property $vigencia
 * @property $observaciones
 * @property $status
 * @property $created_at
 * @property $updated_at
 *
 * @property Airbnbtraveler[] $airbnbtravelers
 * @property Cliente $cliente
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Airbnblink extends Model
{
    
    static $rules = [
		'solicitante' => 'required',
		'cedula' => 'required',
		'celular' => 'required',
		'link' => 'required',
		'vigencia' => 'required',
		'status' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['cliente_id','solicitante','cedula','celular','link','vigencia','observaciones','status'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function airbnbtravelers()
    {
        return $this->hasMany('App\Models\Airbnbtraveler', 'airbnblink_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function cliente()
    {
        return $this->hasOne('App\Models\Cliente', 'id', 'cliente_id');
    }
    

}
