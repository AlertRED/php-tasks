<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class UserGroup extends Model
{
    protected $table = 'user_group';
    protected $fillable = ['name'];
    public $timestamps = false;


    public function users()
	{
	    return $this->belongsToMany('App\User', 'user_groups', 'user_id', 'group_id');
	}

}
