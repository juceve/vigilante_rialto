<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Rrhhdotacion
 *
 * @property $id
 * @property $rrhhcontrato_id
 * @property $empleado_id
 * @property $fecha
 * @property $responsable_entrega
 * @property $estado
 * @property $created_at
 * @property $updated_at
 *
 * @property Empleado $empleado
 * @property Rrhhcontrato $rrhhcontrato
 * @property Rrhhdetalledotacion[] $rrhhdetalledotacions
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Rrhhdotacion extends Model
{
    
    static $rules = [
		'fecha' => 'required',
		'responsable_entrega' => 'required',
		'estado' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['rrhhcontrato_id','empleado_id','fecha','responsable_entrega','estado'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function empleado()
    {
        return $this->hasOne('App\Models\Empleado', 'id', 'empleado_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function rrhhcontrato()
    {
        return $this->hasOne('App\Models\Rrhhcontrato', 'id', 'rrhhcontrato_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rrhhdetalledotacions()
    {
        return $this->hasMany('App\Models\Rrhhdetalledotacion', 'rrhhdotacion_id', 'id');
    }
    

}
