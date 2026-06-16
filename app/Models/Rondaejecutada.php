<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Rondaejecutada
 *
 * @property $id
 * @property $ronda_id
 * @property $cliente_id
 * @property $user_id
 * @property $inicio
 * @property $fin
 * @property $latitud_inicio
 * @property $longitud_inicio
 * @property $latitud_fin
 * @property $longitud_fin
 * @property $status
 * @property $created_at
 * @property $updated_at
 *
 * @property Cliente $cliente
 * @property Rondaejecutadaubicacione[] $rondaejecutadaubicaciones
 * @property Ronda $ronda
 * @property User $user
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Rondaejecutada extends Model
{

    static $rules = [
		'status' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['ronda_id','cliente_id','user_id','inicio','fin','latitud_inicio','longitud_inicio','latitud_fin','longitud_fin','status'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function cliente()
    {
        return $this->hasOne('App\Models\Cliente', 'id', 'cliente_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rondaejecutadaubicaciones()
    {
        return $this->hasMany(Rondaejecutadaubicacione::class, 'rondaejecutada_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function ronda()
    {
        return $this->hasOne('App\Models\Ronda', 'id', 'ronda_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }


}
