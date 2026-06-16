<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Rrhhestadodotacion
 *
 * @property $id
 * @property $nombre
 * @property $created_at
 * @property $updated_at
 *
 * @property Rrhhdotacion[] $rrhhdotacions
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Rrhhestadodotacion extends Model
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
    protected $fillable = ['nombre'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rrhhdotacions()
    {
        return $this->hasMany('App\Models\Rrhhdotacion', 'rrhhestadodotacion_id', 'id');
    }
    

}
