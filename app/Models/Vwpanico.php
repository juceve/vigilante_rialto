<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vwpanico extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'fechahora', 'fecha', 'prioridad', 'user_id', 'detalle', 'latitud', 'longitud', 'visto', 'cliente_id', 'cliente'];

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function imgregistros(): HasMany
    {
        return $this->hasMany(Imgregistro::class);
    }

    public function cliente()
    {
        return $this->hasOne('App\Models\Cliente', 'id', 'cliente_id');
    }
}
