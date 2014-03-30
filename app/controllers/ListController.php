<?php

class ListController extends \BaseController {


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
		//
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