<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Ctrlpunto
 *
 * @property $id
 * @property $nombre
 * @property $hora
 * @property $latitud
 * @property $longitud
 * @property $turno_id
 * @property $created_at
 * @property $updated_at
 *
 * @property Turno $turno
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Ctrlpunto extends Model
{

  static $rules = [
    'hora' => 'required',
    'latitud' => 'required',
    'longitud' => 'required',
  ];

  protected $perPage = 20;

  /**
   * Attributes that should be mass-assignable.
   *
   * @var array
   */
  protected $fillable = ['nombre', 'hora', 'latitud', 'longitud', 'turno_id'];


  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasOne
   */
  public function turno()
  {
    return $this->hasOne('App\Models\Turno', 'id', 'turno_id');
  }


  public function regrondas()
  {
    return $this->hasMany('App\Models\Regronda', 'turno_id', 'id');
  }
}
