<?php

class DollController extends \BaseController {

	public function __construct()
    {
        $this->beforeFilter('admin', array('only' => array('create','edit')) );
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{

		// Get doll types for use in category select element
		$all_types = DollType::get();
		$types['all'] = 'All';
		foreach ( $all_types as $single_type ){
			$types[$single_type->id] = $single_type->title;
		}

		// List of Years Loopsies available for select menu
		$years['all'] = 'All';
		foreach ( range(2010, date('Y')) as $number ){
			$years[$number] = $number;
		}

		// Dolls this user has
		$dolls = array();
		if ( Auth::check() ){
			$list = ToyList::where('user_id', Auth::user()->id)->first()->pluck('id');
			$have = DB::table('dolls_lists')->where('list_id', $list)->where('status', 1)->select('doll_id')->get();
			foreach($have as $key=>$has){
				$dolls[$key] = $has->doll_id;
			}
		}

		$loopsies = Doll::get();
		return View::make('dolls.index')
			->with('loopsies', $loopsies)
			->with('types', $types)
			->with('years', $years)
			->with('dolls', $dolls);
	}


	/**
	 * Show the form for creating a new toy.
	 *
	 * @return View
	 */
	public function create()
	{
		// Get doll types for use in category select element
		$all_types = DollType::get();
		foreach ( $all_types as $single_type ){
			$types[$single_type->id] = $single_type->title;
		}


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
		$filename = time() . '-' . $file->getClientOriginalName();
		$uploadSuccess = Input::file('image')->move($destination, $filename);

		// Crop the thumbnail Image and save it
		$thumbnail_filename = public_path() . '/uploads/toys/_thumbs/225x265_' . $filename;
		$original = $destination . '/' . $filename;
		
		$thumbnail = Image::make($original)->crop(225, 265)->save($thumbnail_filename, 80);
		$slug = Str::slug(Input::get('title'));


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
		$toy->dolltypes()->attach(Input::get('type'));
		$newid = $toy->id;

		$message = Input::get('title') . ' has been added!';

		return Redirect::route('loopsy.update', array('id'=>$newid))
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
		$loopsy = Doll::where('slug', $slug)->first();
		$pagetitle = 'Loopsy List - ' . $loopsy->title;
		
		// Save the formatted birthday for display
		$birthday_month = date('F', $loopsy->sewn_on_month);
		$birthday_day = date('jS', $loopsy->sewn_on_day);
		$birthday = $birthday_month . ' ' . $birthday_day;

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
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$doll = Doll::findOrFail($id);

		// Get doll types for use in category select element
		$all_types = DollType::get();
		foreach ( $all_types as $single_type ){
			$types[$single_type->id] = $single_type->title;
		}

		$image = public_path() . '/uploads/toys/' . $doll->image;
		$image_size = getimagesize($image);

		return View::make('dolls.edit')
			->with('types', $types)
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

		return Redirect::route('loopsy.edit', array('id'=>$id))
			->with('success', 'Loopsy successfully updated');
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

}