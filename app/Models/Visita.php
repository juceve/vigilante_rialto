<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Visita
 *
 * @property $id
 * @property $nombre
 * @property $docidentidad
 * @property $residente
 * @property $nrovivienda
 * @property $motivo_id
 * @property $otros
 * @property $imgs
 * @property $estado
 * @property $created_at
 * @property $updated_at
 *
 * @property Motivo $motivo
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Visita extends Model
{

  static $rules = [
    'nombre' => 'required',
  ];

  protected $perPage = 20;

  /**
   * Attributes that should be mass-assignable.
   *
   * @var array
   */
  protected $fillable = ['nombre', 'docidentidad', 'residente', 'nrovivienda', 'motivo_id', 'otros', 'observaciones', 'imgs', 'designacione_id', 'estado', 'created_at'];


  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasOne
   */
  public function motivo()
  {
    return $this->hasOne('App\Models\Motivo', 'id', 'motivo_id');
  }

  public function designacione()
  {
    return $this->hasOne(Designacione::class, 'id', 'designacione_id');
  }
}
