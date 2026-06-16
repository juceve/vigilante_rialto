<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Designacioneturno
 *
 * @property $id
 * @property $designacione_id
 * @property $turnoguardia_id
 * @property $created_at
 * @property $updated_at
 *
 * @property Designacione $designacione
 * @property Turnoguardia $turnoguardia
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Designacioneturno extends Model
{
    
    static $rules = [
		'designacione_id' => 'required',
		'turnoguardia_id' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['designacione_id','turnoguardia_id'];


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
    public function turnoguardia()
    {
        return $this->hasOne('App\Models\Turnoguardia', 'id', 'turnoguardia_id');
    }
    

}
