<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Airbnbcompanion
 *
 * @property $id
 * @property $airbnbtraveler_id
 * @property $name
 * @property $birth_date
 * @property $document_type
 * @property $document_number
 * @property $nationality
 * @property $luggage_count
 * @property $created_at
 * @property $updated_at
 *
 * @property Airbnbtraveler $airbnbtraveler
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Airbnbcompanion extends Model
{
    
    static $rules = [
		'airbnbtraveler_id' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['airbnbtraveler_id','name','birth_date','document_type','document_number','nationality','luggage_count'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function airbnbtraveler()
    {
        return $this->hasOne('App\Models\Airbnbtraveler', 'id', 'airbnbtraveler_id');
    }
    

}
