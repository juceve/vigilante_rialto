<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Designacionsupervisorcliente
 *
 * @property $id
 * @property $designacionsupervisor_id
 * @property $cliente_id
 * @property $created_at
 * @property $updated_at
 *
 * @property Cliente $cliente
 * @property Designacionsupervisor $designacionsupervisor
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Designacionsupervisorcliente extends Model
{
    
    static $rules = [
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['designacionsupervisor_id','cliente_id'];


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
    public function designacionsupervisor()
    {
        return $this->hasOne('App\Models\Designacionsupervisor', 'id', 'designacionsupervisor_id');
    }
    

}
