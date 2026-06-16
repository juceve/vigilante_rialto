<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Airbnbtraveler extends Model
{
    
    static $rules = [
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['airbnblink_id','arrival_date','departure_date','name','department_info','birth_date','document_type','document_number','city_of_origin','marital_status','address','city','country','email','phone','occupation','luggage_count','company','travel_purpose','reg_in','reg_out','status'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function airbnbcompanions()
    {
        return $this->hasMany(Airbnbcompanion::class,'airbnbtraveler_id','id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function airbnblink()
    {
        return $this->hasOne('App\Models\Airbnblink', 'id', 'airbnblink_id');
    }
    

}
