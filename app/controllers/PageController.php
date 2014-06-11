<?php

use Loopsy\Entities\Doll\EloquentDollRepository;

class PageController extends BaseController {

	/**
	* Doll Repository
	*/
	protected $doll;


	function __construct(EloquentDollRepository $doll)
	{
		$this->doll = $doll;
	}


	/**
	* Site Home Page
	*/
	public function getIndex()
	{
		$count = $this->doll->dollTypeCount('full-size');
		
		return View::make('pages.index')
			->with('front_page', 'front_page')
			->with('count', $count);
	}

}