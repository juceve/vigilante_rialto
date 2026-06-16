<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Turnoguardia
 *
 * @property $id
 * @property $nombre
 * @property $horainicio
 * @property $horafin
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Turnoguardia extends Model
{
    
    static $rules = [
		'nombre' => 'required',
		'horainicio' => 'required',
		'horafin' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['nombre','horainicio','horafin'];



}
