<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Rrhhcargo
 *
 * @property $id
 * @property $nombre
 * @property $descripcion
 * @property $created_at
 * @property $updated_at
 *
 * @property Rrhhcontrato[] $rrhhcontratos
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Rrhhcargo extends Model
{
    
    static $rules = [
		'nombre' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['nombre','descripcion'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rrhhcontratos()
    {
        return $this->hasMany('App\Models\Rrhhcontrato', 'rrhhcargo_id', 'id');
    }
    

}
