<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'email', 'password', 'address', 'landmark', 'country', 'state', 'city', 'pincode', 'mobile', 'profile_image', 'role_id', 'api_token', 'flag', 'tax_exempt', 'email_verified', 'remember_token', 'wp_id', 'company_name', 'validity_date'
    ];

    public function location()
    {
        return $this->hasOne('App\Models\Location', 'pincode', 'pincode');
    }

    public function AauthAcessToken()
    {
        return $this->hasMany('App\Models\OauthAccessToken');
    }

    public function role()
    {
        return $this->belongsTo('App\Models\Role', 'role_id');
    }

    public function documents()
    {
        $date = date('Y-m-d');
        return $this->hasMany('App\Models\UserDocument')->select('user_id','file_type','file_name','created_at')->where('is_deleted',0)->whereDate('created_at', $date);
    }
}
