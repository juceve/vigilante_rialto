<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Ronda
 *
 * @property $id
 * @property $cliente_id
 * @property $nombre
 * @property $descripcion
 * @property $estado
 * @property $created_at
 * @property $updated_at
 *
 * @property Cliente $cliente
 * @property Rondapunto[] $rondapuntos
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Ronda extends Model
{
    
    static $rules = [
		'cliente_id' => 'required',
		'nombre' => 'required',
		'estado' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['cliente_id','nombre','descripcion','estado'];


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
    public function rondapuntos()
    {
        return $this->hasMany('App\Models\Rondapunto', 'ronda_id', 'id');
    }
    

}
