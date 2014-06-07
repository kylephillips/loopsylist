<?php namespace Loopsy\Entities\DollType;

class DollType extends \Eloquent {

	protected $table = 'dolltypes';

	protected $fillable = array(
		'title', 'slug', 'description'
	);

	public function dolls()
	{
		return $this->belongsToMany('Doll', 'dolls_dolltypes');
	}

}