<?php namespace Loopsy\Entities\Wishlist;

class EloquentWishlistRepository {


	/**
	* Get an array of doll ids the user has in their wishlist
	* @return array
	*/
	public function userWishlistArray()
	{
		$wishlist = array();
		if ( \Auth::check() ) {
			$dolls = Wishlist::where('user_id', \Auth::user()->id)->select('doll_id')->get();
			foreach($dolls as $key=>$doll){
				$wishlist[$key] = $doll->doll_id;
			}
		}
		return $wishlist;
	}

}