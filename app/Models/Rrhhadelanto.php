<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Class Rrhhadelanto
 *
 * @property $id
 * @property $rrhhcontrato_id
 * @property $empleado_id
 * @property $fecha
 * @property $mes
 * @property $motivo
 * @property $monto
 * @property $documento_adjunto
 * @property $estado
 * @property $created_at
 * @property $updated_at
 *
 * @property Empleado $empleado
 * @property Rrhhcontrato $rrhhcontrato
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Rrhhadelanto extends Model
{

    static $rules = [
        'fecha' => 'required',
        'mes' => 'required',
        'monto' => 'required',
        'documento_adjunto' => 'required',
        'estado' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['rrhhcontrato_id', 'empleado_id', 'fecha', 'mes', 'motivo', 'monto', 'documento_adjunto', 'estado', 'activo'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function empleado()
    {
        return $this->hasOne('App\Models\Empleado', 'id', 'empleado_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function rrhhcontrato()
    {
        return $this->hasOne('App\Models\Rrhhcontrato', 'id', 'rrhhcontrato_id');
    }

    public static function estadoOptions(): array
    {
        // Consulta directa al esquema de la tabla para extraer los valores del ENUM
        $type = DB::selectOne("SHOW COLUMNS FROM rrhhadelantos WHERE Field = 'estado'")->Type;

        // Extrae los valores del ENUM usando regex
        preg_match('/^enum\((.*)\)$/', $type, $matches);

        // Limpia las comillas y devuelve el array asociativo
        return collect(explode(',', $matches[1]))
            ->mapWithKeys(fn($value) => [
                trim($value, "'") => ucfirst(str_replace('_', ' ', trim($value, "'")))
            ])
            ->toArray();
    }
}
