<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SupBoleta
 *
 * @property $id
 * @property $fechahora
 * @property $cliente_id
 * @property $empleado_id
 * @property $tipoboleta_id
 * @property $supervisor_id
 * @property $detalles
 * @property $descuento
 * @property $created_at
 * @property $updated_at
 *
 * @property Cliente $cliente
 * @property Empleado $empleado
 * @property Empleado $empleado
 * @property Tipoboleta $tipoboleta
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class SupBoleta extends Model
{
    
    static $rules = [
		'fechahora' => 'required',
		'descuento' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['fechahora','cliente_id','empleado_id','tipoboleta_id','supervisor_id','detalles','descuento'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function cliente()
    {
        return $this->hasOne('App\Models\Cliente', 'id', 'cliente_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function empleado()
    {
        return $this->hasOne('App\Models\Empleado', 'id', 'empleado_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function supervisor()
    {
        return $this->hasOne('App\Models\Empleado', 'id', 'supervisor_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function tipoboleta()
    {
        return $this->hasOne('App\Models\Tipoboleta', 'id', 'tipoboleta_id');
    }
    

}
