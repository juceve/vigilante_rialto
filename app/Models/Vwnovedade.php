<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vwnovedade extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'designacione_id', 'fecha', 'hora', 'contenido', 'lat', 'lng', 'empleado_id', 'empleado', 'cliente_id', 'cliente', 'turno_id', 'turno',];

    public function designacione()
    {
        return $this->hasOne('App\Models\Designacione', 'id', 'designacione_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function imgnovedades()
    {
        return $this->hasMany('App\Models\Imgnovedade', 'novedade_id', 'id');
    }
}
