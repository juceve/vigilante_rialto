<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Marcacione
 *
 * @property $id
 * @property $designacione_id
 * @property $fecha
 * @property $hora
 * @property $marcacion
 * @property $created_at
 * @property $updated_at
 *
 * @property Designacione $designacione
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Marcacione extends Model
{
    
    static $rules = [
		'designacione_id' => 'required',
		'fecha' => 'required',
		'hora' => 'required',
		'marcacion' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['designacione_id','fecha','hora','marcacion','lat','lng'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function designacione()
    {
        return $this->hasOne('App\Models\Designacione', 'id', 'designacione_id');
    }
    

}
