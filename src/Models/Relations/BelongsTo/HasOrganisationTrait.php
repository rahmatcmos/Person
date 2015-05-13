<?php namespace ThunderID\Person\Models\Relations\BelongsTo;

trait HasOrganisationTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasOrganisationTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN ORGANISATION PACKAGE -------------------------------------------------------------------*/
	public function Organisation()
	{
		return $this->belongsTo('ThunderID\Organisation\Models\Organisation');
	}
}