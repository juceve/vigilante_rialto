<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{

    static $rules = [
        'nombres' => 'required',
        'apellidos' => 'required',
        'cedula' => 'required',
        'expedido' => 'required',
        'direccion' => 'required',
        'telefono' => 'required',
        'email' => 'required|email|unique:empleados',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['nombres', 'apellidos', 'tipodocumento_id', 'cedula', 'expedido', 'fecnacimiento', 'nacionalidad', 'direccion', 'direccionlat', 'direccionlng', 'telefono', 'imgperfil', 'cedulaanverso', 'cedulareverso', 'cubrerelevos', 'email','enfermedades','alergias','persona_referencia','telefono_referencia','parentezco_referencia', 'area_id', 'oficina_id', 'user_id'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function area()
    {
        return $this->hasOne('App\Models\Area', 'id', 'area_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function tipodocumento()
    {
        return $this->hasOne('App\Models\Tipodocumento', 'id', 'tipodocumento_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function oficina()
    {
        return $this->hasOne('App\Models\Oficina', 'id', 'oficina_id');
    }

    public function designaciones()
    {
        return $this->hasMany(Designacione::class, 'empleado_id', 'id');
    }

    public function contratos()
    {
        return $this->hasMany(Rrhhcontrato::class, 'empleado_id', 'id');
    }
    public function rrhhpermisos()
{
    return $this->hasMany(Rrhhpermiso::class, 'empleado_id');
}
}
