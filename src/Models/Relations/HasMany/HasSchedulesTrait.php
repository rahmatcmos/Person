<?php namespace ThunderID\Person\Models\Relations\HasMany;

trait HasSchedulesTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasSchedulesTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN PERSON PACKAGE -------------------------------------------------------------------*/

	public function Schedules()
	{
		return $this->hasMany('ThunderID\Schedule\Models\PersonSchedule');
	}

	public function ScopeSchedule($query, $variable)
	{
		return $query->whereHas('schedules' ,function($q)use($variable){$q->ondate($variable['on']);});
	}

	public function Workleaves()
	{
		return $this->hasMany('ThunderID\Schedule\Models\PersonSchedule');
	}

	public function ScopeWorkleave($query, $variable)
	{
		return $query->whereHas('workleaves' ,function($q)use($variable){$q->status($variable['status'])->ondate($variable['on']);})
					->whereHas('works' ,function($q)use($variable){$q->id($variable['chartid']);});
	}
}