<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Asistencia
 *
 * @property $id
 * @property $designacione_id
 * @property $fecha
 * @property $ingreso
 * @property $salida
 * @property $latingreso
 * @property $lngingreso
 * @property $latsalida
 * @property $lngsalida
 * @property $created_at
 * @property $updated_at
 *
 * @property Designacione $designacione
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Asistencia extends Model
{

    static $rules = [
		'fecha' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['designacione_id','fecha','ingreso','salida','latingreso','lngingreso','latsalida','lngsalida','estado'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function designacione()
    {
        return $this->hasOne('App\Models\Designacione', 'id', 'designacione_id');
    }


}
