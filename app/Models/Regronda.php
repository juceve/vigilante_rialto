<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Regronda extends Model
{

    static $rules = [
        'empleado_id' => 'required',
        'designacione_id' => 'required',
        'ctrlpunto_id' => 'required',
        'fecha' => 'required',
        'hora' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['empleado_id', 'designacione_id', 'ctrlpunto_id', 'fecha', 'hora', 'anotaciones', 'latA', 'lngA'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function ctrlpunto()
    {
        return $this->hasOne('App\Models\Ctrlpunto', 'id', 'ctrlpunto_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function designacione()
    {
        return $this->hasOne('App\Models\Designacione', 'id', 'designacione_id');
    }

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
    public function imgrondas()
    {
        return $this->hasMany('App\Models\Imgronda', 'regronda_id', 'id');
    }
}
