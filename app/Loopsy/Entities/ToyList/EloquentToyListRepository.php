<?php namespace Loopsy\Entities\ToyList;

use \Auth;

class EloquentToyListRepository {

	/**
	* ToyList Model
	*/
	protected $toylist;


	function __construct(ToyList $toylist)
	{
		$this->toylist = $toylist;
	}

	/**
	* Get a user's toylist by id
	*/
	public function getUserList($loggedin = true)
	{
		if ( $loggedin == true ){
			return $this->toylist->where('user_id', Auth::user()->id)->firstOrFail();
		}
	}

}