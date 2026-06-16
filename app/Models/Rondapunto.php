<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Rondapunto
 *
 * @property $id
 * @property $ronda_id
 * @property $latitud
 * @property $longitud
 * @property $created_at
 * @property $updated_at
 *
 * @property Ronda $ronda
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Rondapunto extends Model
{

    static $rules = [
        'ronda_id' => 'required',
        'descripcion' => 'required',
        'latitud' => 'required',
        'longitud' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['ronda_id', 'descripcion', 'latitud', 'longitud'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function ronda()
    {
        return $this->hasOne('App\Models\Ronda', 'id', 'ronda_id');
    }
}
