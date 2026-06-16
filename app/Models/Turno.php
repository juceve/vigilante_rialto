<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Turno
 *
 * @property $id
 * @property $nombre
 * @property $cliente_id
 * @property $horainicio
 * @property $horafin
 * @property $created_at
 * @property $updated_at
 *
 * @property Cliente $cliente
 * @property Ctrlpunto[] $ctrlpuntos
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Turno extends Model
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
    protected $fillable = ['nombre', 'cliente_id', 'horainicio', 'horafin'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function cliente()
    {
        return $this->hasOne('App\Models\Cliente', 'id', 'cliente_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ctrlpuntos()
    {
        return $this->hasMany('App\Models\Ctrlpunto', 'turno_id', 'id');
    }
}
