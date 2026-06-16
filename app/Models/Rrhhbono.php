<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Rrhhbono
 *
 * @property $id
 * @property $rrhhcontrato_id
 * @property $fecha
 * @property $rrhhtipobono_id
 * @property $empleado_id
 * @property $cantidad
 * @property $monto
 * @property $estado
 * @property $created_at
 * @property $updated_at
 *
 * @property Empleado $empleado
 * @property Rrhhcontrato $rrhhcontrato
 * @property Rrhhtipobono $rrhhtipobono
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Rrhhbono extends Model
{

    static $rules = [
		'fecha' => 'required',
		'cantidad' => 'required',
		'monto' => 'required',
		'estado' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['rrhhcontrato_id','fecha','rrhhtipobono_id','empleado_id','cantidad','monto','pagado','estado'];


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
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function rrhhtipobono()
    {
        return $this->hasOne('App\Models\Rrhhtipobono', 'id', 'rrhhtipobono_id');
    }


}
