<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Detallecotizacione
 *
 * @property $id
 * @property $citecotizacion_id
 * @property $detalle
 * @property $cantidad
 * @property $precio
 * @property $created_at
 * @property $updated_at
 *
 * @property Citecotizacion $citecotizacion
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Detallecotizacione extends Model
{
    
    static $rules = [
		'detalle' => 'required',
		'cantidad' => 'required',
		'precio' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['citecotizacion_id','detalle','cantidad','precio'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function citecotizacion()
    {
        return $this->hasOne('App\Models\Citecotizacion', 'id', 'citecotizacion_id');
    }
    

}
