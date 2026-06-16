<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vwronda extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'empleado_id', 'empleado', 'cliente_id', 'cliente', 'turno_id', 'turno', 'lat', 'lng', 'fecha', 'hora', 'anotaciones', 'ctrpunto_id'];

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
