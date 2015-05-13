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

	/* ------------------------------------------------------------------- RELATIONSHIP IN ORGANISATION VIA WORK PACKAGE -------------------------------------------------------------------*/
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

	public function WorksCalendars()
	{
		return $this->hasMany('ThunderID\Work\Models\Work');
	}

	public function scopeCheckWork($query, $variable)
	{
		if(strtotime($variable))
		{
			$days = new DateTime($variable);
			return $query->whereHas('works', function($q)use($days){$q->where('start', '>=', $days->format('Y-m-d'));});
		}
		if($variable==false)
		{
			return $query->whereDoesntHave('works', function($q)use($variable){$q;});
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
		return $query->with(['works' => function($q){$q->whereNull('end')->orderBy('start', 'asc');}, 'works.branch.organisation' => function($q)use($variable){$q->where('organisations.name', 'like', '%'.$variable.'%');}]);
	}

	public function scopeCheckApps($query, $variable)
	{
		return $query->with(['works.applications']);
	}

	public function scopeCurrentWorkOn($query, $variable)
	{
		if(is_array($variable) && count($variable)==2)
		{
			return $query->whereHas('works.branch', function($q)use($variable){$q->where('branches.name', 'like', '%'.$variable[0].'%')->where('charts.tag', 'like', '%'.$variable[1].'%');});
		}

		return $query->whereHas('works.branch', function($q)use($variable){$q->where('branches.name', 'like', '%'.$variable.'%');});
	}

	public function scopeExperiences($query, $variable)
	{
		return $query->with(['experiences' => function($q)use($variable){$q->orderBy($variable, 'asc')->take(10);}, 'experiences.branch.organisation']);
	}

	public function ScopeWorkCalendar($query, $variable)
	{
		if(isset($variable['id']))
		{
			return $query->whereHas('workscalendars.calendar' ,function($q)use($variable){$q->where('calendar_id', $variable['id']);});
		}
		return $query->whereHas('workscalendars' ,function($q)use($variable){$q;});
	}

	public function ScopeWorkCalendarSchedule($query, $variable)
	{
		return $query->whereHas('workscalendars.calendar.schedules' ,function($q)use($variable){$q->ondate($variable['on']);});
	}


	public function ScopeCheckWorkleave($query, $variable)
	{
		if(strtotime($variable))
		{
			$days = new DateTime($variable);
			return $query->whereHas('works.workleaves', function($q)use($days){$q->ondate([$days->format('Y-m-d'), $days->format('Y-m-d')]);});
		}
		if($variable==false)
		{
			return $query->whereDoesntHave('works.workleaves', function($q)use($variable){$q;});
		}
		return $query->whereHas('works.workleaves', function($q)use($variable){$q;});
	}
}