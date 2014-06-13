<?php namespace Loopsy\Entities\Wishlist;

class Wishlist extends \Eloquent {

	protected $table = 'wishlists';

	protected $fillable = array(
		'doll_id', 'user_id'
	);

	public function users()
	{
		return $this->belongsToMany('Loopsy\Entities\User\User', 'wishlists', 'user_id', 'doll_id')->withTimestamps();
	}


}