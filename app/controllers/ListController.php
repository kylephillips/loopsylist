<?php

class ListController extends \BaseController {

	public function __construct()
    {
        $this->beforeFilter('auth', array('only' => array('edit')) );
    }


	/**
	 * Display the specified resource.
	 *
	 * @param  string  $user Users slug
	 * @return Response
	 */
	public function show($user)
	{
		$user = User::with('ToyList')->where('slug', $user)->firstOrFail();

		// Get doll types for use in category select element
		$all_types = DollType::get();
		foreach ( $all_types as $single_type ){
			$types[$single_type->slug] = $single_type->title;
		}

		// Get the years to populate the select menu
		$years = Doll::distinct()->orderBy('release_year', 'DESC')->get(array('release_year'));
		$years = $years->toArray();
		
		return View::make('lists.show')
			->with('user', $user)
			->with('types', $types)
			->with('years', $years);
	}


	/**
	 * Show the form for editing the list
	 *
	 * @param  string  $id - User's slug
	 * @return Response
	 */
	public function edit($user)
	{
		// Get the User ID & make sure this is their list
		$user = User::with('toylist')->where('slug', $user)->firstOrFail();
		if ( Auth::user()->id == $user->id ) {

			// Get doll types for use in category select element
			$all_types = DollType::get();
			foreach ( $all_types as $single_type ){
				$types[$single_type->slug] = $single_type->title;
			}

			// Latest Release Year
			$latest_year = Doll::orderBy('release_year', 'DESC')->pluck('release_year');

			return View::make('lists.edit')
				->with('types', $types)
				->with('latest_year', $latest_year);
		} else {
			// Redirect them to their list
			return Redirect::route('list.show', array('id'=>Auth::user()->slug));
		}
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}


	/**
	* Single Update switch
	*
	* @return Response
	*/
	public function statusSwitch()
	{
		if (Request::ajax()){
			
			$list = ToyList::where('user_id', Auth::user()->id)->first();
			$status = Input::get('status');
			$doll = Input::get('doll');

			if ( $status == 'yes' ){
				$list->dolls()->detach($doll);
				$list->dolls()->attach($doll, array('status' => 1));
			} else {
				$list->dolls()->detach($doll);
			}

			return Response::json(array('status'=>'success'));
		}
	}



	/**
	* Get list of dolls for user (AJAX)
	*
	* @return Response
	*/
	public function getUserDolls()
	{
		if ( !Input::get('user') ){
			$userid = Auth::user()->id;
		} else {
			$userid = Input::get('user'); 
		}

		$type = Input::get('type');
		$year = Input::get('year');
		$status = Input::get('status');


		// Start building the query
		$query = DB::table('dolls')
				->join('dolls_dolltypes', 'dolls.id', '=', 'dolls_dolltypes.doll_id')
				->join('dolltypes', function($join) use ($type)
					{
						$join->on('dolltypes.id', '=', 'dolls_dolltypes.doll_type_id')
						->where('dolltypes.slug', '=', $type);
					});
		
		$query->join('lists', function($join) use ($userid)
				{
					$join->on('lists.user_id', '=', DB::raw($userid));
				});
		
		// Status Options
		if ( (!Input::get('status')) || (Input::get('status') == 'all') ){
			$query->leftJoin('dolls_lists', function($join)
					{
						$join->on('dolls_lists.list_id', '=', 'lists.id')
						->on('dolls_lists.doll_id','=', 'dolls.id');
					});
		} elseif ( $status == 'no' ) {
			$query->leftJoin('dolls_lists', function($join) use ($status)
					{
						$join->on('dolls_lists.list_id', '=', 'lists.id')
						->on('dolls_lists.doll_id','=', 'dolls.id')
						->where('dolls_lists.status', '!=', DB::raw('IS NOT NULL'));
					});
		} else {
			$query->join('dolls_lists', function($join) use ($status)
					{
						$join->on('dolls_lists.list_id', '=', 'lists.id')
						->on('dolls_lists.doll_id','=', 'dolls.id')
						->where('dolls_lists.status', '=', '1');
					});
		}

		// Limit to year if provided (only for full size dolls)
		if ( Input::get('year') && ( Input::get('type') == 'full-size' ) ){
			$query->where('dolls.release_year', $year);
		}

		$query->select('dolls.id','dolls.title','dolls.release_year','dolls.image','dolls.slug','dolltypes.title as type','dolls_lists.order as order','dolls_lists.status as status');
		
		if ( $status == 'yes' ){	
			$query->orderBy('release_year', 'DESC');
		} else {
			$query->orderBy('dolls_lists.order', 'ASC');
		}

		$results = $query->get();

		// Remove null and 0 values from returned results if status is no
		if ( $status == 'no' ){
			foreach($results as $key=>$result){
				if ( $result->status == 1 ){
					unset($results[$key]);
				} else {
					$result->status = 0;
				}
			}
			return $results;
		}

		return $results;
	}



	/**
	* Reorder the 'dont have' list
	*/
	public function reorder()
	{
		if ( Request::ajax() ){

			$orders = explode(',', $_GET['order']);
			$userid = Auth::user()->id;
			$list = Toylist::with('dolls')->where('user_id',$userid)->firstOrFail();

			foreach ( $orders as $key=>$item ){
				$neworder[] = $key;
				$list->dolls()->detach($item);
				$list->dolls()->attach($item, array('order'=>$key, 'status'=>0));
			}
		}
	}



}