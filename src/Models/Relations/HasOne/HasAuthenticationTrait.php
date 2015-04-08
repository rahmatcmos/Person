<?php namespace ThunderID\Person\Models\Relations\HasOne;

trait HasAuthenticationTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasAuthenticationTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN PERSON PACKAGE -------------------------------------------------------------------*/

	public function Authentication()
	{
		return $this->hasOne('ThunderID\API_Auth\Models\Authentication');
	}
}