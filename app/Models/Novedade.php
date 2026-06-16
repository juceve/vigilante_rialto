<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Novedade
 *
 * @property $id
 * @property $designacione_id
 * @property $fecha
 * @property $hora
 * @property $contenido
 * @property $lat
 * @property $lng
 * @property $created_at
 * @property $updated_at
 *
 * @property Designacione $designacione
 * @property Imgnovedade[] $imgnovedades
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Novedade extends Model
{
    
    static $rules = [
		'designacione_id' => 'required',
		'contenido' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['designacione_id','fecha','hora','contenido','lat','lng'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function designacione()
    {
        return $this->hasOne('App\Models\Designacione', 'id', 'designacione_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function imgnovedades()
    {
        return $this->hasMany('App\Models\Imgnovedade', 'novedade_id', 'id');
    }
    

}
