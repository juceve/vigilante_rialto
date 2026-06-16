<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Rrhhtipodescuento
 *
 * @property $id
 * @property $nombre
 * @property $nombre_corto
 * @property $monto
 * @property $created_at
 * @property $updated_at
 *
 * @property Rrhhdescuento[] $rrhhdescuentos
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Rrhhtipodescuento extends Model
{

    static $rules = [
		'nombre' => 'required',
		'nombre_corto' => 'required|max:10',
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
    public function rrhhdescuentos()
    {
        return $this->hasMany('App\Models\Rrhhdescuento', 'rrhhtipodescuento_id', 'id');
    }


}
