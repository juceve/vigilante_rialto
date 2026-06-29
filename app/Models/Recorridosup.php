<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Recorridosup
 *
 * @property $id
 * @property $designacionsupervisor_id
 * @property $fecha_hora
 * @property $latitud
 * @property $longitud
 * @property $created_at
 * @property $updated_at
 *
 * @property Designacionsupervisor $designacionsupervisor
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Recorridosup extends Model
{
    
    static $rules = [
		'designacionsupervisor_id' => 'required',
		'fecha_hora' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['designacionsupervisor_id','fecha_hora','latitud','longitud'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function designacionsupervisor()
    {
        return $this->hasOne('App\Models\Designacionsupervisor', 'id', 'designacionsupervisor_id');
    }
    

}
