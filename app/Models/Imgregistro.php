<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Imgregistro
 *
 * @property $id
 * @property $registroguardia_id
 * @property $url
 * @property $tipo
 * @property $created_at
 * @property $updated_at
 *
 * @property Registroguardia $registroguardia
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Imgregistro extends Model
{
    
    static $rules = [
		'registroguardia_id' => 'required',
		'url' => 'required',
		'tipo' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['registroguardia_id',  'url','tipo'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function registroguardia()
    {
        return $this->hasOne('App\Models\Registroguardia', 'id', 'registroguardia_id');
    }
    

}
