<?php

use Loopsy\Entities\Doll\EloquentDollRepository;
use Loopsy\Entities\DollType\EloquentDollTypeRepository;
use Loopsy\Entities\User\EloquentUserRepository;
use Loopsy\Entities\Doll\Doll;
use Loopsy\Entities\ToyList\ToyList;
use Loopsy\Entities\DollType\DollType;

class DollController extends \BaseController {

	/**
	* Doll Repository
	*/
	private $doll;

	/**
	* DollType Repository
	*/
	private $dolltype;

	/**
	* User Repository
	*/
	private $user;


	public function __construct(EloquentDollRepository $doll, EloquentDollTypeRepository $dolltype, EloquentUserRepository $user)
    {
    	$this->doll = $doll;
    	$this->dolltype = $dolltype;
    	$this->user = $user;
        $this->beforeFilter('admin', array('only' => array('create','edit')) );
    }
	
	/**
	 * Show all the dolls
	 *
	 * @return Response
	 */
	public function index()
	{
		// Variables for use in query
		$queried_year = ( Input::get('year') ) ? Input::get('year') : '';
		$queried_type = ( Input::get('type') ) ? $this->dolltype->getSlugBySlug(Input::get('type')) : 'full-size';
		
		// Select Menu Filters
		$dolltypes = $this->dolltype->selectArray();
		$years = $this->doll->yearList();

		// Get the list of Loopsies & what the user has
		$dolls_owned = $this->user->dollsUserHasArray();
		$loopsies = $this->doll->getDollsFiltered($queried_year, $queried_type);
		
		return View::make('dolls.index')
			->with('loopsies', $loopsies)
			->with('types', $dolltypes)
			->with('years', $years)
			->with('dolls', $dolls_owned)
			->with('year', $queried_year)
			->with('type', $queried_type);
	}


	/**
	 * Show the form for creating a new toy.
	 *
	 * @return View
	 */
	public function create()
	{
		$types = $this->dolltype->selectArray('id');
		return View::make('dolls.create')
			->with('types', $types);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$validation = Validator::make(Input::all(), Doll::$required, Doll::$validation_messages);
		if ( $validation->fails() ){
			$messages = $validation->messages();
			return Redirect::route('loopsy.create')
				->withInput()
				->withErrors($validation);
		}

		// Add the Image
		$file = Input::file('image');
		$destination = public_path() . '/uploads/toys';
		$slug = Str::slug(Input::get('title'));

		$filename = time() . '-' . $slug;
		$uploadSuccess = Input::file('image')->move($destination, $filename);

		// Crop the thumbnail Image and save it
		$thumbnail_filename = public_path() . '/uploads/toys/_thumbs/225x265_' . $filename;
		$original = $destination . '/' . $filename;
		
		$thumbnail = Image::make($original)->crop(225, 265)->save($thumbnail_filename, 80);


		// Save the new toy
		$toy = Doll::create(array(
			'title' => Input::get('title'),
			'slug' => $slug,
			'image' => $filename,
			'sewn_from' => Input::get('sewn_from'),
			'pet' => Input::get('pet'),
			'bio' => Input::get('bio'),
			'link' => Input::get('link'),
			'release_month' => Input::get('release_month'),
			'release_year' => Input::get('release_year'),
			'sewn_on_month' => Input::get('sewn_on_month'),
			'sewn_on_day' => Input::get('sewn_on_day')
		));

		// Save the toy Type
		$toy->dolltypes()->sync(array(Input::get('type')));
		$newid = $toy->id;

		$message = Input::get('title') . ' has been added!';

		return Redirect::route('loopsy.edit', array('id'=>$newid))
			->withSuccess($message);

	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($slug)
	{
		$loopsy = $this->doll->getBySlug($slug);
		
		// Formatted Vars for View
		$birthday_month = date('F', $loopsy->sewn_on_month);
		$birthday_day = date('jS', $loopsy->sewn_on_day);
		$birthday = $birthday_month . ' ' . $birthday_day;
		$pagetitle = 'Loopsy List - ' . $loopsy->title;

		// Does the user have this doll?
		$status = 0;
		if ( Auth::check() ){
			$list = Auth::user()->toylist->id;
			$status = DB::table('dolls_lists')->where('doll_id',$loopsy->id)->where('list_id', $list)->pluck('status');
			if ( !isset($status) ) $status = 0;
		}
		return View::make('dolls.show')
			->with('pagetitle', $pagetitle)
			->with('loopsy', $loopsy)
			->with('birthday', $birthday)
			->with('status', $status);
	}

	/**
	 * Show the form for editing the Doll.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($slug)
	{
		$doll = $this->doll->getBySlug($slug);
		$types = $this->dolltype->selectArray('id');
		$type_id = $doll->dolltypes->first()->id;

		$image = public_path() . '/uploads/toys/' . $doll->image;
		$image_size = getimagesize($image);

		return View::make('dolls.edit')
			->with('types', $types)
			->with('dolltype', $type_id)
			->with('doll', $doll)
			->with('image_size', $image_size);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$validation = Validator::make(Input::all(), Doll::$required, Doll::$validation_messages);
		if ( $validation->fails() ){
			$messages = $validation->messages();
			return Redirect::route('loopsy.edit', array('id'=>$id))
				->withInput()
				->withErrors($validation);
		}

		$doll = Doll::findOrFail($id);

		// Recrop the image if new crop is present
		if ( Input::get('cropimage') ){
			$x = Input::get('x');
			$y = Input::get('y');
			$w = Input::get('w');
			$h = Input::get('h');
				
			$file = public_path() . '/uploads/toys/' . $doll->image; // Original Image Path
			$resized = public_path() . '/uploads/toys/_thumbs/225x265_' . $doll->image; // Image to overwrite
			
			$newcrop = Image::make($file)->crop($w, $h, $x, $y)->resize(225, 265)->save($resized);
		}

		// Update the remaining fields
		if ( Input::get('title') ) $doll->title = Input::get('title');
		if ( Input::get('title') ) $doll->slug = Str::slug(Input::get('title'));
		if ( Input::get('sewn_from') ) $doll->sewn_from = Input::get('sewn_from');
		if ( Input::get('pet') ) $doll->pet = Input::get('pet');
		if ( Input::get('bio') ) $doll->bio = Input::get('bio');
		if ( Input::get('link') ) $doll->link = Input::get('link');
		$doll->release_month = Input::get('release_month');
		$doll->release_year = Input::get('release_year');
		$doll->sewn_on_month = Input::get('sewn_on_month');
		$doll->sewn_on_day = Input::get('sewn_on_day');
		$doll->save();

		$doll->dolltypes()->sync(array(Input::get('type')));

		return Redirect::route('loopsy.edit', array('id'=>$doll->slug))
			->with('success', 'Loopsy successfully updated');
	}


	/**
	 * Delete the Doll
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}