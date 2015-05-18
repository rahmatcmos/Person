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
}