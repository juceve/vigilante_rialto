<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Intervention\Image\ImageManagerStatic as Image;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;


    static $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:8',
    ];

    static $rulesUpdate = [
        'name' => 'required|string|max:255',
        'email' => 'required|email',
    ];

    static $rulesPassword = [
        'current_password' => 'required',
        'password' => 'required|string|min:8|confirmed',
    ];

    static $rulesAvatar = [
        'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    ];

    public function adminlte_desc()
    {
        $user = Auth::user();

        return $user->roles[0]->name;
    }

    protected $fillable = [
        'name',
        'email',
        'password',
        'template',
        'avatar',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function adminlte_image()
    {
        if ($this->avatar && file_exists(public_path('uploads/avatars/' . $this->avatar))) {
            return asset('uploads/avatars/' . $this->avatar);
        }
        return asset('images/escudo2.png');
    }

    public function getAvatarUrlAttribute()
    {
        if ($this->avatar && file_exists(public_path('uploads/avatars/' . $this->avatar))) {
            return asset('uploads/avatars/' . $this->avatar);
        }
        return asset('images/escudo2.png');
    }

    public function empleados()
    {
        return $this->hasMany('App\Models\Empleado', 'user_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function registroguardias()
    {
        return $this->hasMany('App\Models\Registroguardia', 'user_id', 'id');
    }

    public function adminlte_profile_url()
    {
        return 'admin/profile';
    }

    public function propietario()
    {
        return $this->hasOne(Propietario::class, 'user_id', 'id');
    }
}
