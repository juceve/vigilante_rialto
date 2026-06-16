<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Dialibre
 *
 * @property $id
 * @property $fecha
 * @property $designacione_id
 * @property $created_at
 * @property $updated_at
 *
 * @property Designacione $designacione
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Dialibre extends Model
{

  static $rules = [
    'fecha' => 'required',
    'designacione_id' => 'required',
  ];

  protected $perPage = 20;

  /**
   * Attributes that should be mass-assignable.
   *
   * @var array
   */
  protected $fillable = ['fecha', 'observaciones', 'designacione_id'];


  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasOne
   */
  public function designacione()
  {
    return $this->hasOne('App\Models\Designacione', 'id', 'designacione_id');
  }
}
