<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ChklRespuesta
 *
 * @property $id
 * @property $chkl_ejecucione_id
 * @property $chkl_pregunta_id
 * @property $ok
 * @property $observacion
 * @property $created_at
 * @property $updated_at
 *
 * @property ChklEjecucione $chklEjecucione
 * @property ChklIncumplimiento[] $chklIncumplimientos
 * @property ChklPregunta $chklPregunta
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class ChklRespuesta extends Model
{
    
    static $rules = [
		'ok' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['chkl_ejecucione_id','chkl_pregunta_id','ok','observacion'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function chklEjecucione()
    {
        return $this->hasOne('App\Models\ChklEjecucione', 'id', 'chkl_ejecucione_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function chklIncumplimientos()
    {
        return $this->hasMany('App\Models\ChklIncumplimiento', 'chkl_respuesta_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function chklPregunta()
    {
        return $this->hasOne('App\Models\ChklPregunta', 'id', 'chkl_pregunta_id');
    }
    

}
