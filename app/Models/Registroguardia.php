<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Registroguardia
 *
 * @property $id
 * @property $fechahora
 * @property $prioridad
 * @property $user_id
 * @property $detalle
 * @property $latitud
 * @property $longitud
 * @property $created_at
 * @property $updated_at
 *
 * @property User $user
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Registroguardia extends Model
{
    
    static $rules = [
		'fechahora' => 'required',
		'prioridad' => 'required',
		'user_id' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['fechahora','prioridad','user_id','detalle','latitud','longitud','visto','cliente_id','created_at'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
    
    public function imgregistros():HasMany
    {
      return $this->hasMany(Imgregistro::class);
    }

    public function cliente()
    {
        return $this->hasOne('App\Models\Cliente', 'id', 'cliente_id');
    }
}
