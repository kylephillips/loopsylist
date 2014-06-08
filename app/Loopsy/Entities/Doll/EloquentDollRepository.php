<?php namespace Loopsy\Entities\Doll;

class EloquentDollRepository {

	/**
	* Get a Doll by Slug
	* @return Eloquent Model
	*/
	public function getBySlug($slug)
	{
		return Doll::with('dolltypes')->where('slug', $slug)->first();
	}


	/**
	* List of Years Available
	* @return array
	*/
	public function yearList($all_option = true)
	{
		if ( $all_option == true ) $years['all'] = 'All';
		$all_years = Doll::orderBy('release_year', 'desc')->groupBy('release_year')->select('release_year')->get();
		foreach ( $all_years as $year ){
			$years[$year->release_year] = $year->release_year;
		}
		return $years;
	}


	/**
	* Get an Eloquent Collection of dolls based on provided parameters
	*/
	public function getDollsFiltered($year = '', $type = '')
	{
		$dolls = Doll::where(function($query) use ($type) {

			if ( (isset($_GET['year'])) && ($_GET['year'] !== 'all') ){
				$query->where('release_year', $_GET['year']);
			}

			$query->whereHas('dolltypes', function($q) use ($type) {
				$q->where('slug', $type);
			});

		})->get();
		return $dolls;
	}

	
}