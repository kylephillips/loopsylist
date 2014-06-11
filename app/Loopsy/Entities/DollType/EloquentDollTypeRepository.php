<?php namespace Loopsy\Entities\DollType;

use \DB;

class EloquentDollTypeRepository {


	/**
	* Get a Doll Type by it's slug
	* @return string
	*/
	public function getSlugBySlug($slug)
	{
		return DB::table('dolltypes')->where('slug', $slug)->pluck('slug');
	}


	/**
	* Return an Array of Types for use in Select
	* @return array
	*/
	public function selectArray($value = 'slug')
	{
		$alltypes = DollType::all();
		foreach ( $alltypes as $type ){
			if ( $value == 'slug' ) {
				$types[$type->slug] = $type->title;
			} else {
				$types[$type->id] = $type->title;
			}
		}
		return $types;
	}

}