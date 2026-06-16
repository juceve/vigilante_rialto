<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Rrhhferiado
 *
 * @property $id
 * @property $nombre
 * @property $fecha
 * @property $fecha_inicio
 * @property $fecha_fin
 * @property $recurrente
 * @property $factor
 * @property $activo
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Rrhhferiado extends Model
{
    
    static $rules = [
		'nombre' => 'required',
		'recurrente' => 'required',
		'factor' => 'required',
		'activo' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['nombre','fecha','fecha_inicio','fecha_fin','recurrente','factor','activo'];



}
