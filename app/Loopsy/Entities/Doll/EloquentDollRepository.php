<?php namespace Loopsy\Entities\Doll;

use \DB;

class EloquentDollRepository {

	/**
	* Doll Model
	*/
	protected $doll;


	function __construct(Doll $doll)
	{
		$this->doll = $doll;
	}


	/**
	* Get the count of a given doll-type
	* @return int
	*/
	public function dollTypeCount($type)
	{
		$count = $this->doll->whereHas('dolltypes', function($q) use ($type) {
			$q->where('slug', $type);
		})->count();

		return $count;
	}


	/**
	* Get a Doll by Slug
	* @return Eloquent Model
	*/
	public function getBySlug($slug)
	{
		return $this->doll->with('DollTypes')->where('slug', $slug)->firstOrFail();
	}


	/**
	* List of Years Available
	* @return array
	*/
	public function yearList($all_option = true)
	{
		if ( $all_option == true ) $years['all'] = 'All';
		$all_years = $this->doll->orderBy('release_year', 'desc')->groupBy('release_year')->select('release_year')->get();
		foreach ( $all_years as $year ){
			$years[$year->release_year] = $year->release_year;
		}
		return $years;
	}


	/**
	* Return the latest year in the DB
	* @return string
	*/
	public function latestYear()
	{
		$year = $this->doll->orderBy('release_year', 'DESC')->pluck('release_year');
		return $year;
	}


	/**
	* Get an Dolls based on provided parameters
	* @return Eloquent Object
	*/
	public function getDollsFiltered($year = '', $type = '')
	{
		$dolls = $this->doll->where(function($query) use ($type, $year) {

			if ( ($year) && ($year !== 'all') ){
				$query->where('release_year', $year);
			}

			$query->whereHas('dolltypes', function($q) use ($type) {
				$q->where('slug', $type);
			});

		})->get();

		return $dolls;
	}


	/**
	* Get User Dolls
	*/
	public function getUserDolls($user = null, $type = null, $year = null, $status = null)
	{
		$query = DB::table('dolls')
				->join('dolls_dolltypes', 'dolls.id', '=', 'dolls_dolltypes.doll_id')
				->join('dolltypes', function($join) use ($type)
					{
						$join->on('dolltypes.id', '=', 'dolls_dolltypes.doll_type_id')
						->where('dolltypes.slug', '=', $type);
					});
		
		$query->join('lists', function($join) use ($user)
				{
					$join->on('lists.user_id', '=', DB::raw($user));
				});

		// Add Wishlist 
		$query->leftJoin('wishlists', function($join) use ($user)
				{
					$join->on('dolls.id', '=', 'wishlists.doll_id')
					->where('wishlists.user_id', '=', DB::raw($user));
				});
		

		// Filter by status
		if ( (!$status || $status == 'all') ){
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
						->where('dolls_lists.status', '!=', DB::raw('IS NOT NULL'))
						->where('dolls_lists.status', '=', '0');
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
		if ( $year && ( $type == 'full-size' ) ){
			$query->where('dolls.release_year', $year);
		}

		// Fields to select
		$query->select('dolls.id','dolls.title','dolls.release_year','dolls.image','dolls.slug','dolltypes.title as type','dolls_lists.order as order','dolls_lists.status as status', 'wishlists.doll_id as wishlist');
		
		$query->orderBy('release_year', 'DESC');

		$results = $query->get();
		return $results;
	}

	
}