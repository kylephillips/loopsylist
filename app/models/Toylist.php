<?php

class Toylist extends Eloquent {

	protected $table = 'lists';
	
	protected $fillable = array(
		'user_id', 'visibility'
	);
	
	public function user()
	{
		return $this->belongsTo('user');
	}

	public function dolls()
	{
		return $this->belongsToMany('Doll')
			->withPivot('order')
			->withTimestamps();
	}

}