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
				->withPivot('relationship', 'organisation_id');
	}

	public function scopeCheckRelation($query, $variable)
	{
		return $query->select('persons.*', 'persons.id as relative_id')
					 ->join('relatives', 'persons.id', '=', 'relatives.relative_id')
					 ->where('person_id', $variable);
	}

	public function scopeCheckRelative($query, $variable)
	{
		return $query->with(['relatives' => function($q){$q->take(1);}]);
	}
}