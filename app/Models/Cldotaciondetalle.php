<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Cldotaciondetalle
 *
 * @property $id
 * @property $cldotacion_id
 * @property $detalle
 * @property $cantidad
 * @property $rrhhestadodotacion_id
 * @property $created_at
 * @property $updated_at
 *
 * @property Cldotacion $cldotacion
 * @property Rrhhestadodotacion $rrhhestadodotacion
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Cldotaciondetalle extends Model
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
    protected $fillable = ['cldotacion_id','detalle','cantidad','rrhhestadodotacion_id','url'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function cldotacion()
    {
        return $this->hasOne('App\Models\Cldotacion', 'id', 'cldotacion_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function rrhhestadodotacion()
    {
        return $this->hasOne('App\Models\Rrhhestadodotacion', 'id', 'rrhhestadodotacion_id');
    }
    

}
