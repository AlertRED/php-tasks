<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;


/**
 * @SWG\Definition(
 *  definition="UserGroup",
 *  @SWG\Property(
 *      property="id",
 *      type="integer",
 *		example=1
 *  ),
 *  @SWG\Property(
 *      property="name",
 *      type="string",
 *		example="Group 1"
 *  ),
 *  @SWG\Property(
 *      property="timestamps",
 *      type="bool",
 *		example=false
 *  )
 * )
 */
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
