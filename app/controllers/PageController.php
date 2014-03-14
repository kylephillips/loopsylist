<?php
class PageController extends BaseController {

	public function getIndex()
	{
		return View::make('pages.index')
			->with('front_page', 'front_page');
	}

	/**
	* Display the find a list form
	* @return View
	*/
	public function getSearch()
	{
		return View::make('pages.find-list');
	}

}