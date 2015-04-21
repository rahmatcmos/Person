<?php namespace ThunderID\Person\Models\Relations\BelongsToMany;

use DateTime;

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
		if(strtotime($variable))
		{
			$days = new DateTime($variable);
			return $query->whereHas('works', function($q)use($days){$q->where('start', '>=', $days->format('Y-m-d'));});
		}
		return $query->whereHas('works', function($q)use($variable){$q;});
	}

	public function scopeCheckResign($query, $variable)
	{
		if(strtotime($variable))
		{
			$days = new DateTime($variable);
			return $query->whereHas('experiences', function($q)use($days){$q->where('end', '<', $days->format('Y-m-d'));});
		}
		return $query->whereHas('experiences', function($q)use($variable){$q->where('end', '<', $variable);});
	}

	public function scopeCurrentWork($query, $variable)
	{
		return $query->with(['works' => function($q){$q->whereNull('end')->orderBy('updated_at', 'asc');}, 'works.branch.organisation' => function($q)use($variable){$q->where('organisations.name', 'like', '%'.$variable.'%');}, 'works.applications']);
	}

	public function scopeCurrentWorkOn($query, $variable)
	{
		return $query->whereHas('works.branch', function($q)use($variable){$q->where('branches.name', 'like', '%'.$variable.'%');});
	}

	public function scopeExperiences($query, $variable)
	{
		return $query->with(['experiences' => function($q)use($variable){$q->orderBy($variable, 'asc')->take(10);}, 'experiences.branch.organisation']);
	}
}