<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Citecotizacion
 *
 * @property $id
 * @property $correlativo
 * @property $gestion
 * @property $cite
 * @property $fecha
 * @property $fechaliteral
 * @property $destinatario
 * @property $cargo
 * @property $monto
 * @property $estado
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Citecotizacion extends Model
{

  static $rules = [
    'correlativo' => 'required',
    'gestion' => 'required',
    'cite' => 'required',
    'fecha' => 'required',
    'fechaliteral' => 'required',
    'destinatario' => 'required',
    'cargo' => 'required',
    'monto' => 'required',
    'estado' => 'required',
  ];

  protected $perPage = 20;

  /**
   * Attributes that should be mass-assignable.
   *
   * @var array
   */
  protected $fillable = ['correlativo', 'gestion', 'cite', 'fecha', 'fechaliteral', 'destinatario', 'cargo', 'monto', 'estado'];

  public function detalles()
  {
    return $this->hasMany(Detallecotizacione::class, 'citecotizacion_id', 'id');
  }
}
