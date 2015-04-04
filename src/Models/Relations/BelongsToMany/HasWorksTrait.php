<?php namespace ThunderID\Person\Models\Relations\BelongsToMany;

trait HasWorksTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasWorksTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN WORK PACKAGE -------------------------------------------------------------------*/
	public function Works()
	{
		return $this->belongsToMany('ThunderID\Organisation\Models\OrganisationChart', 'works', 'person_id', 'organisation_chart_id');
	}
}