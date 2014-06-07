<?php namespace Loopsy\Entities\Group;

class Group extends \Eloquent {

	protected $table = 'groups';
	protected $fillable = array(
		'title', 'slug'
	);

	public function users()
	{
		return $this->hasMany('Loopsy\Entities\User\User');
	}

}