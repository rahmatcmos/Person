<?php namespace ThunderID\Person\Models\Relations\HasMany;

trait HasWidgetsTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasWidgetsTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN WIDBOARD PACKAGE -------------------------------------------------------------------*/

	public function Widgets()
	{
		return $this->hasMany('ThunderID\Widboard\Models\PersonWidget');
	}

	public function ScopeCheckWidget($query, $variable)
	{
		return $query->with(['widgets' => function($q)use($variable){$q->orderBy($variable, 'asc');}]);
	}
}