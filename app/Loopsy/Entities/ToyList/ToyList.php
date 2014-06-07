<?php namespace Loopsy\Entities\ToyList;

class ToyList extends \Eloquent {

	protected $table = 'lists';
	
	protected $fillable = array(
		'user_id', 'visibility'
	);
	
	public function user()
	{
		return $this->belongsTo('User');
	}

	public function dolls()
	{
		return $this->belongsToMany('Loopsy\Entities\Doll\Doll', 'dolls_lists', 'list_id', 'doll_id')
			->withPivot('order')
			->withPivot('status')
			->withTimestamps();
	}

	public function hasDolls()
	{
		return $this->dolls()->wherePivot('status', '1');
	}

}