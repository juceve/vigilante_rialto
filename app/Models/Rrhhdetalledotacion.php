<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Rrhhdetalledotacion
 *
 * @property $id
 * @property $rrhhdotacion_id
 * @property $detalle
 * @property $cantidad
 * @property $rrhhestadodotacion_id
 * @property $created_at
 * @property $updated_at
 *
 * @property Rrhhdotacion $rrhhdotacion
 * @property Rrhhestadodotacion $rrhhestadodotacion
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Rrhhdetalledotacion extends Model
{
    
    static $rules = [
		'detalle' => 'required',
		'cantidad' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['rrhhdotacion_id','detalle','cantidad','rrhhestadodotacion_id','url'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function rrhhdotacion()
    {
        return $this->hasOne('App\Models\Rrhhdotacion', 'id', 'rrhhdotacion_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function rrhhestadodotacion()
    {
        return $this->hasOne('App\Models\Rrhhestadodotacion', 'id', 'rrhhestadodotacion_id');
    }
    

}
