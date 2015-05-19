<?php namespace ThunderID\Person\Models\Relations\HasMany;

use DB;

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

	/* ------------------------------------------------------------------- RELATIONSHIP IN SCHEDULE PACKAGE -------------------------------------------------------------------*/

	public function Schedules()
	{
		return $this->hasMany('ThunderID\Schedule\Models\PersonSchedule');
	}

	public function ScopeSchedule($query, $variable)
	{
		return $query->whereHas('schedules' ,function($q)use($variable){$q->ondate($variable['on']);});
	}

	public function TakenWorkleaves()
	{
		return $this->hasMany('ThunderID\Schedule\Models\PersonSchedule');
	}

	public function ScopeTakenWorkleave($query, $variable)
	{
		return $query->with(['takenworkleaves' => function($q)use($variable){$q->status($variable['status'])->ondate($variable['on']);}]);
	}

	public function ScopeFullSchedule($query, $variable)
	{
		// if(is_array($variable))
		// {
		// 	return $query
		// 			->selectRaw('if(hr_person_schedules.on >= "'.$variable[0].'" && hr_person_schedules.on <= "'.$variable[1].'", hr_person_schedules.on, if(hr_schedules.on >= "'.$variable[0].'" && hr_schedules.on <= "'.$variable[1].'", hr_schedules.on, "Not Scheduled")) as ondate')
		// 			->selectRaw('hr_persons.name')
		// 			->Join('works', 'persons.id', '=', 'works.person_id')
		// 			->Join('calendars', 'calendars.id', '=', 'works.calendar_id')
		// 			->leftJoin('person_schedules', 'persons.id', '=', 'person_schedules.person_id')
		// 			->leftJoin('schedules', 'calendars.id', '=', 'schedules.calendar_id')
		// 			->whereRaw('NOT EXISTS (SELECT * FROM hr_logs WHERE hr_logs.on >= "'.$variable[0].'" && hr_logs.on <= "'.$variable[1].'" && hr_logs.person_id = hr_persons.id)')
		// 			->whereNull('works.end')
		// 			->orderBy(DB::Raw('if(hr_person_schedules.on >= "'.$variable[0].'" && hr_person_schedules.on <= "'.$variable[1].'", hr_person_schedules.on, if(hr_schedules.on >= "'.$variable[0].'" && hr_schedules.on <= "'.$variable[1].'", hr_schedules.on, "Not Scheduled"))'), 'desc')
		// 			->groupBy('persons.id')
		// 			;			
		// }
		return $query
					->selectRaw('if(hr_person_schedules.on = "'.$variable.'", hr_person_schedules.on, if(hr_schedules.on = "'.$variable.'", hr_schedules.on, "'.$variable.'")) as ondate')
					->selectRaw('if(hr_person_schedules.on = "'.$variable.'", hr_person_schedules.start, if(hr_schedules.on = "'.$variable.'", hr_schedules.start, "'.$variable.'")) as onstart')
					->selectRaw('if(hr_person_schedules.on = "'.$variable.'", hr_person_schedules.end, if(hr_schedules.on = "'.$variable.'", hr_schedules.end, "'.$variable.'")) as onend')
					->selectRaw('hr_persons.name')
					->Join('works', 'persons.id', '=', 'works.person_id')
					->Join('calendars', 'calendars.id', '=', 'works.calendar_id')
					->leftJoin('person_schedules', 'persons.id', '=', 'person_schedules.person_id')
					->leftJoin('schedules', 'calendars.id', '=', 'schedules.calendar_id')
					->whereRaw('NOT EXISTS (SELECT * FROM hr_logs WHERE hr_logs.on = "'.$variable.'" && hr_logs.person_id = hr_persons.id)')
                    ->whereNull('works.end')
                    ->groupBy('persons.id')
					;
	}

	public function ScopeMinusQuotas($query, $variable)
	{
		return $query->selectRaw('sum(is_affect_salary) as minus_quota')
					->selectRaw('status')
					->selectRaw('person_id')
					->join('person_schedules', 'persons.id', '=', 'person_schedules.person_id')
					->whereIn('persons.id', $variable['ids'])
					->where('person_schedules.on', '>=', date('Y-m-d',strtotime($variable['ondate'][0])))
					->where('person_schedules.on', '<=', date('Y-m-d',strtotime($variable['ondate'][1])))
					->where('is_affect_salary', true)
					->groupBy('status')
					->groupBy('persons.id');
	}
}