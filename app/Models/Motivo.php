<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Motivo
 *
 * @property $id
 * @property $nombre
 * @property $estado
 * @property $created_at
 * @property $updated_at
 *
 * @property Visita[] $visitas
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Motivo extends Model
{
    
    static $rules = [
		'nombre' => 'required',
		'estado' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['nombre','estado'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function visitas()
    {
        return $this->hasMany('App\Models\Visita', 'motivo_id', 'id');
    }
    

}
