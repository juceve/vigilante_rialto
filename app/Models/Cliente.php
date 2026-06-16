<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cliente extends Model
{

    static $rules = [
        'nombre' => 'required',
        'nrodocumento' => 'required',
        'direccion' => 'required',
        'uv' => 'required',
        'manzano' => 'required',
        'latitud' => 'required',
        'longitud' => 'required',
        'personacontacto' => 'required',
        'telefonocontacto' => 'required',
        'oficina_id' => 'required',
        'status' => 'required',
    ];

    protected $perPage = 20;

    protected $fillable = ['nombre', 'tipodocumento_id', 'nrodocumento', 'direccion', 'uv', 'manzano', 'latitud', 'longitud', 'personacontacto', 'telefonocontacto', 'oficina_id', 'observaciones', 'email', 'fecha_inicio', 'fecha_fin', 'status'];

    public function oficina()
    {
        return $this->hasOne('App\Models\Oficina', 'id', 'oficina_id');
    }

    public function tipodocumento()
    {
        return $this->hasOne('App\Models\Tipodocumento', 'id', 'tipodocumento_id');
    }


    public function turnos(): HasMany
    {
        return $this->hasMany(Turno::class);
    }

    public function rondas(): HasMany
    {
        return $this->hasMany(Ronda::class, 'cliente_id', 'id');
    }

    public function cuestionarios(): HasMany
    {
        return $this->hasMany(ChklListaschequeo::class, 'cliente_id', 'id');
    }
}
