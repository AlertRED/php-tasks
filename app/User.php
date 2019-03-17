<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;


/**
 * @SWG\Definition(
 *  definition="User",
 *  @SWG\Property(
 *      property="id",
 *      type="integer",
 *      example=1
 *  ),
 *  @SWG\Property(
 *      property="name",
 *      type="string",
 *      example="Group 1"
 *  ),
 *  @SWG\Property(
 *      property="email",
 *      type="string",
 *      example="qwerty@mail.ru"
 *  ),
 *  @SWG\Property(
 *      property="password",
 *      type="string",
 *      example="qwerty"
 *  ),
 *  @SWG\Property(
 *      property="banned",
 *      type="bool",
 *      example=false
 *  ),
 *  @SWG\Property(
 *      property="role",
 *      type="string",
 *      example="User"
 *  )
 * )
 */
class User extends Authenticatable
{
    use Notifiable;


    protected $fillable = [
        'name', 'email', 'password', 'banned', 'role'
    ];


    protected $hidden = [
        'password', 'remember_token',
    ];

    public function groups()
    {
        return $this->belongsToMany('App\Models\User\UserGroup','user_groups', 'user_id', 'group_id');
    }
}
