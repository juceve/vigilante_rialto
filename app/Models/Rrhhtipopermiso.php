<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Rrhhtipopermiso
 *
 * @property $id
 * @property $nombre
 * @property $nombre_corto
 * @property $factor
 * @property $color
 * @property $created_at
 * @property $updated_at
 *
 * @property Rrhhpermiso[] $rrhhpermisos
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Rrhhtipopermiso extends Model
{
    
    static $rules = [
		'nombre' => 'required',
		'nombre_corto' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['nombre','nombre_corto','factor','color'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rrhhpermisos()
    {
        return $this->hasMany('App\Models\Rrhhpermiso', 'rrhhtipopermiso_id', 'id');
    }
    

}
