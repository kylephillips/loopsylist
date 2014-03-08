<?php

class Doll extends Eloquent {

	protected $table = 'dolls';
	
	protected $fillable = array(
		'title', 'slug', 'image', 'sewn_from', 'pet', 'bio', 'link', 'release_month', 'release_year', 'sewn_on_month', 'sewn_on_day'
	);

	// Required fields for Validation
	public static $required = array(
		'title' => 'required',
		'release_month' => 'required',
		'release_year' => 'required|integer',
		'image' => 'image'
	);

	// Validation messages
	public static $validation_messages = array(
		'title.required' => "Please include a name"
	);

	public function lists()
	{
		return $this->belongsToMany('List')
			->withPivot('order')
			->withTimestamps();
	}

	public function dolltypes()
	{
		return $this->belongsToMany('DollType', 'dolls_dolltypes');
	}

}