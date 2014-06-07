<?php

use Loopsy\Entities\Doll\Doll;

class PageController extends BaseController {

	public function getIndex()
	{
		$count = Doll::whereHas('dolltypes', function($q){
			$q->where('slug','full-size');
		})->count();
		
		return View::make('pages.index')
			->with('front_page', 'front_page')
			->with('count', $count);
	}

}