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
		return $this->belongsToMany('ThunderID\Organisation\Models\OrganisationChart', 'works', 'person_id', 'organisation_chart_id')
					->withPivot('status', 'start', 'end');
	}

	public function scopeCurrentWork($query, $variable)
	{
		return $query->with(['works' => function($q)use($variable){$q->whereNull('end')->orderBy($variable);}, 'works.branch.organisation']);
	}
}