<?php

class ListController extends \BaseController {

	public function __construct()
    {
        $this->beforeFilter('auth', array('only' => array('show','edit')) );
    }


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		// Get the User ID
		$user = User::with('toylist')->where('slug', $id)->firstOrFail();
		if ( Auth::user()->id == $user->id ) {

			// Get doll types for use in category select element
			$all_types = DollType::get();
			foreach ( $all_types as $single_type ){
				$types[$single_type->slug] = $single_type->title;
			}

			// Get the dolls this user has
			$have = DB::table('dolls_lists')
				->where('list_id', $user->toylist->id)
				->where('status', 1)
				->select('doll_id')
				->get();
			foreach($have as $key=>$has){
				$dolls_have[$key] = $has->doll_id;
			}

			// FPO: only fullsize
			$dolls = Doll::whereHas('dolltypes', function($q){
					$q->where('slug', 'full-size');
				})->orderBy('release_year', 'DESC')->get();


			return View::make('lists.show')
				->with('types', $types)
				->with('dolls', $dolls)
				->with('dolls_have', $dolls_have);
		} else {
			// Redirect them to their list
			return Redirect::route('list.show', array('id'=>Auth::user()->slug));
		}
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
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
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

	/**
	* Single Update switch (on loopsy view)
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
				$list->dolls()->attach($doll, array('status' => 1));
			} else {
				$list->dolls()->detach($doll, array('status' => 0));
			}

			return Response::json(array('status'=>'success'));
		}
	}

}