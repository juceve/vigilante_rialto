<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Rrhhdocscontrato
 *
 * @property $id
 * @property $referencia
 * @property $url
 * @property $rrhhcontrato_id
 * @property $created_at
 * @property $updated_at
 *
 * @property Rrhhcontrato $rrhhcontrato
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Rrhhdocscontrato extends Model
{
    
    static $rules = [
		'referencia' => 'required',
		'url' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['referencia','url','rrhhcontrato_id'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function rrhhcontrato()
    {
        return $this->hasOne('App\Models\Rrhhcontrato', 'id', 'rrhhcontrato_id');
    }
    

}
