<?php namespace ThunderID\Person\Models\Relations\BelongsToMany;

trait HasRelativesTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasRelativesTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN PERSON PACKAGE -------------------------------------------------------------------*/

	public function Relatives()
	{
		return $this->belongsToMany('ThunderID\Person\Models\Person', 'relatives', 'person_id', 'relative_id')
				->withPivot('relationship', 'id');
	}

	public function Person()
	{
		return $this->belongsToMany('ThunderID\Person\Models\Person', 'relatives', 'person_id', 'relative_id')
				->withPivot('relationship');
	}

	public function scopeCheckRelation($query, $variable)
	{
		return $query->select('persons.*', 'relatives.id as relative_id')
					 ->join('relatives', 'persons.id', '=', 'relatives.person_id')
					 ->where('relative_id', $variable);
	}
}