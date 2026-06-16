<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Cajachica
 *
 * @property $id
 * @property $empleado_id
 * @property $gestion
 * @property $fecha_apertura
 * @property $fecha_cierre
 * @property $estado
 * @property $observacion
 * @property $created_at
 * @property $updated_at
 *
 * @property Empleado $empleado
 * @property Movimientocaja[] $movimientocajas
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Cajachica extends Model
{

    static $rules = [
        'empleado_id' => 'required',
        'gestion' => 'required',
        'fecha_apertura' => 'required',
        'estado' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['empleado_id', 'gestion', 'fecha_apertura', 'fecha_cierre', 'estado', 'observacion'];


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
    public function movimientocajas()
    {
        return $this->hasMany('App\Models\Movimientocaja', 'cajachica_id', 'id');
    }

    protected $appends = ['saldo_actual'];

    public function getSaldoActualAttribute()
    {
        return $this->movimientocajas()
            ->selectRaw("
                COALESCE(SUM(CASE WHEN tipo = 'INGRESO' THEN monto ELSE 0 END), 0) -
                COALESCE(SUM(CASE WHEN tipo = 'EGRESO' THEN monto ELSE 0 END), 0)
                AS saldo
            ")
            ->value('saldo') ?? 0;
    }

    public function totalIngresos()
    {
        return $this->movimientocajas()
            ->where('tipo', 'INGRESO')
            ->sum('monto');
    }

    public function totalEgresos()
    {
        return $this->movimientocajas()
            ->where('tipo', 'EGRESO')
            ->sum('monto');
    }

    public function egresosDelMes($mes = null)
    {
        $mes = $mes ?? now()->month;

        return $this->movimientocajas()
            ->where('tipo', 'EGRESO')
            ->whereMonth('fecha', $mes)
            ->whereYear('fecha', $this->gestion)
            ->sum('monto');
    }

}
