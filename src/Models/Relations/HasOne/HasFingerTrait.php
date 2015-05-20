<?php namespace ThunderID\Person\Models\Relations\HasOne;

trait HasFingerTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasFingerTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN FINGER PACKAGE -------------------------------------------------------------------*/

	public function Finger()
	{
		return $this->hasOne('ThunderID\Finger\Models\Finger');
	}

	public function scopeDisplayUpdatedFinger($query, $variable)
	{
		return $query->select(['contacts.value as email', 'fingers.*'])
					->join('contacts', 'persons.id', '=', 'contacts.person_id')
					->join('fingers', 'persons.id', '=', 'fingers.person_id')
					->where('contacts.item', 'email')
					->where('contacts.is_default', true)
					->where('fingers.updated_at', '>', $variable)
					;
	}
}