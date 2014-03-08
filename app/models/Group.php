<?php
class Group extends Eloquent {

	protected $table = 'groups';
	protected $fillable = array(
		'title', 'slug'
	);

	public function users()
	{
		return $this->hasMany('user');
	}

}