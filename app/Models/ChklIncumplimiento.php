<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ChklIncumplimiento
 *
 * @property $id
 * @property $chkl_respuesta_id
 * @property $empleado_id
 * @property $observaciones
 * @property $created_at
 * @property $updated_at
 *
 * @property ChklRespuesta $chklRespuesta
 * @property Empleado $empleado
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class ChklIncumplimiento extends Model
{
    
    static $rules = [
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['chkl_respuesta_id','empleado_id','observaciones'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function chklRespuesta()
    {
        return $this->hasOne('App\Models\ChklRespuesta', 'id', 'chkl_respuesta_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function empleado()
    {
        return $this->hasOne('App\Models\Empleado', 'id', 'empleado_id');
    }
    

}
