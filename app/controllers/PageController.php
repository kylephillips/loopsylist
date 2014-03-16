<?php
class PageController extends BaseController {

	public function getIndex()
	{
		return View::make('pages.index')
			->with('front_page', 'front_page');
	}

}