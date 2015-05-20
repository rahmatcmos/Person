<?php namespace ThunderID\Person\Models\Relations\HasMany;

use DB;

trait HasPersonWorkleavesTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasPersonWorkleavesTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN SCHEDULE PACKAGE -------------------------------------------------------------------*/

	public function PersonWorkleaves()
	{
		return $this->hasMany('ThunderID\Workleave\Models\PersonWorkleave');
	}

	public function ScopeCheckWorkleave($query, $variable)
	{
		if(is_array($variable))
		{
			return $query->whereHas('personworkleaves', function($q)use($variable){$q->ondate($variable);});
		}
		if($variable==false)
		{
			return $query->whereDoesntHave('personworkleaves', function($q)use($variable){$q;});
		}
		return $query->whereHas('personworkleaves', function($q)use($variable){$q;});
	}

	public function ScopeWorkleaveID($query, $variable)
	{
		return $query->whereHas('personworkleaves', function($q)use($variable){$q->workleaveid($variable);});
	}

	public function ScopeQuotas($query, $variable)
	{
		return $query->CheckWork(true)
					->selectRaw('sum(IF(is_default = true, quota, 0)) as quota')
					->selectRaw('sum(IF(is_default = false, quota, 0)) as plus_quota')
					->selectRaw('hr_persons.*')
					->join('persons_workleaves', 'persons.id', '=', 'persons_workleaves.person_id')
					->join('workleaves','persons_workleaves.workleave_id', '=', 'workleaves.id')
					->where('persons_workleaves.start', '<=', date('Y-m-d',strtotime($variable['ondate'][0])))
					->where('persons_workleaves.end', '>=', date('Y-m-d',strtotime($variable['ondate'][1])))
					->groupBy('persons.id');
	}
}