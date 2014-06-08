<?php namespace Loopsy\Entities\User;

use Loopsy\Entities\ToyList\ToyList;
use Auth;
use DB;

class EloquentUserRepository {

	/**
	* Dolls this user has
	* @return array (simple array of ids)
	*/
	public function dollsUserHasArray()
	{
		$dolls = [];
		if ( Auth::check() ){
			$list = ToyList::where('user_id', Auth::user()->id)->pluck('id');
			$have = DB::table('dolls_lists')->where('list_id', $list)->where('status', 1)->select('doll_id')->get();
			foreach($have as $key=>$has){
				$dolls[$key] = $has->doll_id;
			}
		}
		return $dolls;
	}

}