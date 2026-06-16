<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Imgnovedade
 *
 * @property $id
 * @property $novedade_id
 * @property $url
 * @property $tipo
 * @property $created_at
 * @property $updated_at
 *
 * @property Novedade $novedade
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Imgnovedade extends Model
{
    
    static $rules = [
		'novedade_id' => 'required',
		'url' => 'required',
		'tipo' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['novedade_id','url','tipo'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function novedade()
    {
        return $this->hasOne('App\Models\Novedade', 'id', 'novedade_id');
    }
    

}
