<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ChklPregunta
 *
 * @property $id
 * @property $chkl_listaschequeo_id
 * @property $descripcion
 * @property $tipoboleta_id
 * @property $requerida
 * @property $created_at
 * @property $updated_at
 *
 * @property ChklListaschequeo $chklListaschequeo
 * @property ChklRespuesta[] $chklRespuestas
 * @property Tipoboleta $tipoboleta
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class ChklPregunta extends Model
{
    
    static $rules = [
		'descripcion' => 'required',
		'requerida' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['chkl_listaschequeo_id','descripcion','tipoboleta_id','requerida'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function chklListaschequeo()
    {
        return $this->hasOne('App\Models\ChklListaschequeo', 'id', 'chkl_listaschequeo_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function chklRespuestas()
    {
        return $this->hasMany('App\Models\ChklRespuesta', 'chkl_pregunta_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function tipoboleta()
    {
        return $this->hasOne('App\Models\Tipoboleta', 'id', 'tipoboleta_id');
    }
    

}
