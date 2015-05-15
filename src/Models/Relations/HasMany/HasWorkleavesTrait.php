<?php namespace ThunderID\Person\Models\Relations\HasMany;

trait HasWorkleavesTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasWorkleavesTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN SCHEDULE PACKAGE -------------------------------------------------------------------*/

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