<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ChklListaschequeo
 *
 * @property $id
 * @property $cliente_id
 * @property $titulo
 * @property $descripcion
 * @property $activo
 * @property $created_at
 * @property $updated_at
 *
 * @property ChklEjecucione[] $chklEjecuciones
 * @property ChklPregunta[] $chklPreguntas
 * @property Cliente $cliente
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class ChklListaschequeo extends Model
{
    
    static $rules = [
		'titulo' => 'required',
		'activo' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['cliente_id','titulo','descripcion','activo'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function chklEjecuciones()
    {
        return $this->hasMany('App\Models\ChklEjecucione', 'chkl_listaschequeo_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function chklPreguntas()
    {
        return $this->hasMany('App\Models\ChklPregunta', 'chkl_listaschequeo_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function cliente()
    {
        return $this->hasOne('App\Models\Cliente', 'id', 'cliente_id');
    }
    

}
