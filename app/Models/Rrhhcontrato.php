<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Rrhhcontrato
 *
 * @property $id
 * @property $empleado_id
 * @property $rrhhtipocontrato_id
 * @property $fecha_inicio
 * @property $fecha_fin
 * @property $salario_basico
 * @property $rrhhcargo_id
 * @property $moneda
 * @property $motivo_fin
 * @property $activo
 * @property $created_at
 * @property $updated_at
 *
 * @property Empleado $empleado
 * @property Rrhhadelanto[] $rrhhadelantos
 * @property Rrhhcargo $rrhhcargo
 * @property Rrhhdocscontrato[] $rrhhdocscontratos
 * @property Rrhhpermiso[] $rrhhpermisos
 * @property Rrhhtipocontrato $rrhhtipocontrato
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Rrhhcontrato extends Model
{

    static $rules = [
        'fecha_inicio' => 'required',
        'salario_basico' => 'required',
        'moneda' => 'required',
        'activo' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['empleado_id', 'rrhhtipocontrato_id', 'fecha_inicio', 'fecha_fin', 'salario_basico', 'gestora', 'caja_seguro', 'rrhhcargo_id', 'moneda', 'motivo_fin', 'activo'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function empleado()
    {
        return $this->hasOne('App\Models\Empleado', 'id', 'empleado_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rrhhadelantos()
    {
        return $this->hasMany('App\Models\Rrhhadelanto', 'rrhhcontrato_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function rrhhcargo()
    {
        return $this->hasOne('App\Models\Rrhhcargo', 'id', 'rrhhcargo_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rrhhdocscontratos()
    {
        return $this->hasMany('App\Models\Rrhhdocscontrato', 'rrhhcontrato_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rrhhpermisos()
    {
        return $this->hasMany('App\Models\Rrhhpermiso', 'rrhhcontrato_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function rrhhtipocontrato()
    {
        return $this->belongsTo(Rrhhtipocontrato::class, 'rrhhtipocontrato_id');
    }
}
