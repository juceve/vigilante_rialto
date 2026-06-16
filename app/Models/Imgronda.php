<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Imgronda
 *
 * @property $id
 * @property $regronda_id
 * @property $url
 * @property $tipo
 * @property $created_at
 * @property $updated_at
 *
 * @property Regronda $regronda
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Imgronda extends Model
{
    
    static $rules = [
		'regronda_id' => 'required',
		'url' => 'required',
		'tipo' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['regronda_id','url','tipo'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function regronda()
    {
        return $this->hasOne('App\Models\Regronda', 'id', 'regronda_id');
    }
    

}
