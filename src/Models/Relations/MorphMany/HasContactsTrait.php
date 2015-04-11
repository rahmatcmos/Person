<?php namespace ThunderID\Person\Models\Relations\MorphMany;

trait HasContactsTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasContactsTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN CONTACT PACKAGE -------------------------------------------------------------------*/
	public function Contacts()
	{
		return $this->morphMany('ThunderID\Contact\Models\Contact', 'person');
	}

	public function scopeCurrentContact($query, $variable)
	{
		return $query->with(['contacts' => function($q)use($variable){$q->where('is_default', true)->orderBy($variable, 'asc');}]);
	}
}