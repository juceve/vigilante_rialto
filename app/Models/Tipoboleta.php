<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Tipoboleta
 *
 * @property $id
 * @property $nombre
 * @property $descripcion
 * @property $rrhhtipodescuento_id
 * @property $monto_descuento
 * @property $status
 * @property $created_at
 * @property $updated_at
 *
 * @property Rrhhtipodescuento $rrhhtipodescuento
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Tipoboleta extends Model
{
    
    static $rules = [
		'nombre' => 'required',
		'status' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['nombre','descripcion','rrhhtipodescuento_id','monto_descuento','status'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function rrhhtipodescuento()
    {
        return $this->hasOne('App\Models\Rrhhtipodescuento', 'id', 'rrhhtipodescuento_id');
    }
    

}
