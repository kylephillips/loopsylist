<?php

class Doll extends Eloquent {

	protected $table = 'dolls';
	
	protected $fillable = array(
		'title', 'slug', 'image', 'sewn_from', 'pet', 'bio', 'link', 'release_date'
	);

	public function lists()
	{
		return $this->belongsToMany('List')
			->withPivot('order')
			->withTimestamps();
	}

	public function dolltypes()
	{
		return $this->belongsToMany('DollType');
	}

}