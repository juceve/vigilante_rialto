<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Rondaejecutadaubicacione
 *
 * @property $id
 * @property $rondaejecutada_id
 * @property $latitud
 * @property $longitud
 * @property $hora
 * @property $created_at
 * @property $updated_at
 *
 * @property Rondaejecutada $rondaejecutada
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Rondaejecutadaubicacione extends Model
{

    static $rules = [
		'hora' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['rondaejecutada_id', 'rondapunto_id', 'latitud','longitud','fecha_hora'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function rondaejecutada()
    {
        return $this->hasOne('App\Models\Rondaejecutada', 'id', 'rondaejecutada_id');
    }


}
