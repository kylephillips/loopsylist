<?php

use Loopsy\Entities\Wishlist\Wishlist;
use Loopsy\Entities\User\User;

class WishlistController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Add a doll to the user's wishlist
	 *
	 * @return Response
	 */
	public function create()
	{
		
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		Auth::user()->wishlists()->attach(Input::get('doll'));
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
	 * Update a doll on the wishlist
	 *
	 * @return Response
	 */
	public function update($id)
	{
		
	}

	/**
	 * Remove Doll from Wishlist
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		Auth::user()->wishlists()->detach(Input::get('doll'));
	}

}