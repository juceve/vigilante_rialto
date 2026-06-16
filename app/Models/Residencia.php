<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Residencia
 *
 * @property $id
 * @property $cliente_id
 * @property $propietario_id
 * @property $cedula_propietario
 * @property $numeropuerta
 * @property $piso
 * @property $calle
 * @property $nrolote
 * @property $manzano
 * @property $notas
 * @property $estado
 * @property $created_at
 * @property $updated_at
 *
 * @property Propietario $propietario
 * @property Propietario $propietario
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Residencia extends Model
{
    
    static $rules = [
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['cliente_id','propietario_id','cedula_propietario','numeropuerta','piso','calle','nrolote','manzano','notas','estado'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function propietario()
    {
        return $this->hasOne('App\Models\Propietario', 'id', 'propietario_id');
    }

    public function cliente(){
        return $this->hasOne('App\Models\Cliente', 'id', 'cliente_id');
    }

    public function paseingresos()
    {
        return $this->hasMany('App\Models\Paseingreso', 'residencia_id', 'id');
    }
}
