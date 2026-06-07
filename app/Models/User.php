<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'company_name',
        'email',
        'email_verified_at',
        'password',
        'remember_token',
        'created_at',
        'updated_at',
        'u_type',
        'phone',
        'avatar',
        'addr_one',
        'addr_two',
        'country_id',
        'state_id',
        'city_id',
        'pincode',
        'status',
        'userStatus',
        'isActive',
        'isdeleted',
        'ca_add_by',
        'user_add_by',
        'admin_add_by',
        'emp_permission',
        'ca_permissions',
        'isCaActive',
        'is_online',
        'compId',
        'trial_start_at',
        'trial_days',
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
		'ca_permissions' => 'array',
        'email_verified_at' => 'datetime',
        'status' => 'boolean',
        'userStatus' => 'boolean',
        'isActive' => 'boolean',
        'isdeleted' => 'boolean',
        'isCaActive' => 'boolean',
        'is_online' => 'boolean',
    ];
}
