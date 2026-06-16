<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Flujopase
 *
 * @property $id
 * @property $paseingreso_id
 * @property $fecha
 * @property $tipo
 * @property $hora
 * @property $user_id
 * @property $created_at
 * @property $updated_at
 *
 * @property Paseingreso $paseingreso
 * @property User $user
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Flujopase extends Model
{
    
    static $rules = [
		'fecha' => 'required',
		'tipo' => 'required',
		'hora' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['paseingreso_id','fecha','tipo','hora','anotaciones','user_id'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function paseingreso()
    {
        return $this->hasOne('App\Models\Paseingreso', 'id', 'paseingreso_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
    

}
