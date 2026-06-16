<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Class Rrhhpermiso
 *
 * @property $id
 * @property $rrhhcontrato_id
 * @property $empleado_id
 * @property $rrhhtipopermiso_id
 * @property $fecha_inicio
 * @property $fecha_fin
 * @property $cantidad_horas
 * @property $motivo
 * @property $documento_adjunto
 * @property $activo
 * @property $created_at
 * @property $updated_at
 *
 * @property Empleado $empleado
 * @property Rrhhcontrato $rrhhcontrato
 * @property Rrhhtipopermiso $rrhhtipopermiso
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Rrhhpermiso extends Model
{
    
    static $rules = [
		'fecha_inicio' => 'required',
		'fecha_fin' => 'required',
		'activo' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['rrhhcontrato_id','empleado_id','rrhhtipopermiso_id','fecha_inicio','fecha_fin','cantidad_horas','motivo','documento_adjunto','activo','status'];


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
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function rrhhtipopermiso()
    {
        return $this->hasOne('App\Models\Rrhhtipopermiso', 'id', 'rrhhtipopermiso_id');
    }
    
    /**
     * Devuelve los valores posibles del enum 'status' de la tabla asociada.
     *
     * @return array
     */
    public static function getStatusOptions(): array
    {
        static $cache = null;
        if ($cache !== null) {
            return $cache;
        }

        $instance = new static;
        $table = $instance->getTable();

        $column = DB::selectOne(DB::raw("SHOW COLUMNS FROM `{$table}` WHERE Field = 'status'"));
        if (! $column || ! isset($column->Type)) {
            $cache = [];
            return $cache;
        }

        // Extrae los valores entre enum(...)
        if (preg_match("/^enum\((.*)\)$/", $column->Type, $matches)) {
            $vals = str_getcsv($matches[1], ',', "'");
            $cache = array_map(function($v){ return trim($v, "'\""); }, $vals);
            return $cache;
        }

        $cache = [];
        return $cache;
    }

}
