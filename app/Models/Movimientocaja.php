<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Movimientocaja
 *
 * @property $id
 * @property $cajachica_id
 * @property $fecha
 * @property $tipo
 * @property $monto
 * @property $concepto
 * @property $categoria
 * @property $referencia
 * @property $comprobante
 * @property $created_at
 * @property $updated_at
 *
 * @property Cajachica $cajachica
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Movimientocaja extends Model
{
    
    static $rules = [
		'cajachica_id' => 'required',
		'fecha' => 'required',
		'tipo' => 'required',
		'monto' => 'required',
		'concepto' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['cajachica_id','fecha','tipo','monto','concepto','categoria','referencia','comprobante'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function cajachica()
    {
        return $this->hasOne('App\Models\Cajachica', 'id', 'cajachica_id');
    }
    

}
