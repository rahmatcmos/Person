<?php namespace ThunderID\Person\Models\Relations\HasOne;

trait HasFingerTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasFingerTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN FINGER PACKAGE -------------------------------------------------------------------*/

	public function Finger()
	{
		return $this->hasOne('ThunderID\Finger\Models\Finger');
	}
}