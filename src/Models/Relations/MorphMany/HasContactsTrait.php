<?php namespace ThunderID\Person\Models\Relations\MorphMany;

trait HasContactsTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasContactsTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN CONTACT PACKAGE -------------------------------------------------------------------*/
	public function Contacts()
	{
		return $this->morphMany('ThunderID\Contact\Models\Contact');
	}

}