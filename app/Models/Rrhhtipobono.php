<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Rrhhtipobono
 *
 * @property $id
 * @property $nombre
 * @property $nombre_corto
 * @property $monto
 * @property $created_at
 * @property $updated_at
 *
 * @property Rrhhbono[] $rrhhbonos
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Rrhhtipobono extends Model
{
    
    static $rules = [
		'nombre' => 'required',
		'nombre_corto' => 'required',
		'monto' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['nombre','nombre_corto','monto'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rrhhbonos()
    {
        return $this->hasMany('App\Models\Rrhhbono', 'rrhhtipobono_id', 'id');
    }
    

}
