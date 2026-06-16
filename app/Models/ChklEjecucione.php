<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ChklEjecucione
 *
 * @property $id
 * @property $chkl_listaschequeo_id
 * @property $fecha
 * @property $inspector_id
 * @property $notas
 * @property $created_at
 * @property $updated_at
 *
 * @property ChklListaschequeo $chklListaschequeo
 * @property ChklRespuesta[] $chklRespuestas
 * @property Empleado $empleado
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class ChklEjecucione extends Model
{
    
    static $rules = [
		'fecha' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['chkl_listaschequeo_id','fecha','inspector_id','notas'];


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
        return $this->hasMany('App\Models\ChklRespuesta', 'chkl_ejecucione_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function empleado()
    {
        return $this->hasOne('App\Models\Empleado', 'id', 'inspector_id');
    }
    

}
