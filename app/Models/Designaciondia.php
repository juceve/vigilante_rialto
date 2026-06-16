<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Designaciondia
 *
 * @property $id
 * @property $designacione_id
 * @property $domingo
 * @property $lunes
 * @property $martes
 * @property $miercoles
 * @property $jueves
 * @property $viernes
 * @property $sabado
 * @property $created_at
 * @property $updated_at
 *
 * @property Designacione $designacione
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Designaciondia extends Model
{
    
    static $rules = [
		'designacione_id' => 'required',
		'domingo' => 'required',
		'lunes' => 'required',
		'martes' => 'required',
		'miercoles' => 'required',
		'jueves' => 'required',
		'viernes' => 'required',
		'sabado' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['designacione_id','domingo','lunes','martes','miercoles','jueves','viernes','sabado'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function designacione()
    {
        return $this->hasOne('App\Models\Designacione', 'id', 'designacione_id');
    }
        


}
