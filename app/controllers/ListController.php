<?php

use Loopsy\Entities\DollType\EloquentDollTypeRepository;
use Loopsy\Entities\Doll\EloquentDollRepository;
use Loopsy\Entities\User\EloquentUserRepository;
use Loopsy\Entities\ToyList\EloquentToyListRepository;

class ListController extends BaseController {

	/**
	* DollType Repository
	*/
	protected $dolltype;

	/**
	* Doll Repository
	*/
	protected $doll;

	/**
	* User Repository
	*/
	protected $user;

	/**
	* List Repository
	*/
	protected $toylist;


	public function __construct(EloquentDollTypeRepository $dolltype, EloquentDollRepository $doll, EloquentUserRepository $user, EloquentToyListRepository $toylist)
    {
    	$this->dolltype = $dolltype;
    	$this->doll = $doll;
    	$this->user = $user;
    	$this->toylist = $toylist;
        $this->beforeFilter('auth', array('only' => array('edit')) );
    }


	/**
	 * Display the user's list.
	 *
	 * @param  string  $user Users slug
	 * @return Response
	 */
	public function show($user)
	{
		$user = $this->user->userBySlug($user);
		$types = $this->dolltype->selectArray();
		$years = $this->doll->yearList(false);
		$title = "$user->name's Loopsy List";
		
		return View::make('lists.show')
			->with('user', $user)
			->with('types', $types)
			->with('years', $years)
			->with('title', $title);
	}


	/**
	 * Show the form for editing the list
	 *
	 * @param  string  $user - User's slug
	 * @return Response
	 */
	public function edit($user)
	{
		$user = $this->user->userBySlug($user);
		if ( Auth::user()->id == $user->id ){
			$types = $this->dolltype->selectArray();
			$years = $this->doll->yearList(false);

			return View::make('lists.edit')
				->with('types', $types)
				->with('years', $years);
		}
		return Redirect::route('loopsy.index');
	}


	/**
	 * Update the list (status switch).
	 *
	 * @return Response
	 */
	public function update()
	{
		if ( Request::ajax() ){
			
			$list = $this->toylist->getUserList();
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
		$user = ( !Input::get('user') ) ? Auth::user()->id : Input::get('user');
		$type = Input::get('type');
		$year = Input::get('year');
		$status = Input::get('status');
		$results = $this->doll->getUserDolls($user, $type, $year, $status);
		return $results;
		
	}


	/**
	* Reorder the 'dont have' list
	*/
	public function reorder()
	{
		if ( Request::ajax() ){
			$order = explode(',', Input::get('order'));
			$list = $this->toylist->getUserList();

			foreach ( $order as $key=>$item ){
				$neworder[] = $key;
				$list->dolls()->detach($item);
				$list->dolls()->attach($item, array('order'=>$key, 'status'=>0));
			}
		}
	}



}