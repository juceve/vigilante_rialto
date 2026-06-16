<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Rrhhsueldo
 *
 * @property $id
 * @property $gestion
 * @property $mes
 * @property $fecha
 * @property $hora
 * @property $user_id
 * @property $estado
 * @property $created_at
 * @property $updated_at
 *
 * @property Rrhhsueldoempleado[] $rrhhsueldoempleados
 * @property User $user
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Rrhhsueldo extends Model
{
    
    static $rules = [
		'gestion' => 'required',
		'mes' => 'required',
		'fecha' => 'required',
		'hora' => 'required',
		'user_id' => 'required',
		'estado' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['gestion','mes','fecha','hora','user_id','estado'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rrhhsueldoempleados()
    {
        return $this->hasMany('App\Models\Rrhhsueldoempleado', 'rrhhsueldo_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
    

}
