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
		return $this->belongsToMany('ThunderID\Organisation\Models\Chart', 'works', 'person_id', 'chart_id')
					->withPivot('status', 'start', 'end');
	}

	public function Experiences()
	{
		return $this->belongsToMany('ThunderID\Organisation\Models\Chart', 'works', 'person_id', 'chart_id')
					->withPivot('status', 'start', 'end', 'reason_end_job');
	}

	public function scopeCheckWork($query, $variable)
	{
		return $query->whereHas('works', function($q)use($variable){$q;});
	}

	public function scopeCurrentWork($query, $variable)
	{
		return $query->with(['works' => function($q)use($variable){$q->whereNull('end')->orderBy($variable);}, 'works.branch.organisation', 'works.applications']);
	}

	public function scopeExperiences($query, $variable)
	{
		return $query->with(['experiences' => function($q)use($variable){$q->orderBy($variable, 'asc')->take(10);}, 'experiences.branch.organisation']);
	}
}