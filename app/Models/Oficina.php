<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Oficina extends Model
{
    
    static $rules = [
		'nombre' => 'required',
		'direccion' => 'required',
    ];

    protected $perPage = 20;

    protected $fillable = ['nombre','direccion'];

    public function clientes()
    {
        return $this->hasMany('App\Models\Cliente', 'oficina_id', 'id');
    }   

}
