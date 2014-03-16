<?php
class SearchController extends BaseController {

	/**
	* Display the search form
	* @return View
	*/
	public function getIndex()
	{
		return View::make('search.index');
	}

	/**
	* Process Search Form
	* @return Response
	*/
	public function postSearch()
	{
		if ( Input::get('type') == 'name' ){
			
			// Name Search
			$term = '%' . Input::get('name') . '%';
			
			$results = DB::table('users')
				->select('name', 'slug', 'city', 'state')
				->join('lists', 'users.id', '=', 'lists.user_id')
				->where('lists.visibility', '=', 'public')
				->where('users.name', 'LIKE', $term)
				->get();
			
			return $results;

		} elseif ( Input::get('type') == 'location' ){

			// Location Search
			$lat = Input::get('latitude');
			$lng = Input::get('longitude');

			$results = DB::table('users')
				->select(DB::raw("
					name, 
					slug, 
					city, 
					state,
					( 3959 * acos( 	cos( radians($lat) ) * cos( radians( users.latitude ) ) * 
							cos( radians( users.longitude ) - radians($lng) ) + 
							sin( radians($lat) ) * sin( radians( users.latitude ) ) ) ) AS distance
					"))
				->join('lists', 'users.id', '=', 'lists.user_id')
				->where('lists.visibility', '=', 'public')
				->having('distance', '<', 50)
				->get();

			return $results;

		} else {
			return;
		}
	}


}