<?php namespace ThunderID\Person\Models;

/* ----------------------------------------------------------------------
 * Document Model:
 * 	ID 								: Auto Increment, Integer, PK
 * 	organisation_id 				: Foreign Key From Organisation, Integer, Required
 * 	person_id 						: Foreign Key From Person, Integer, Required
 * 	relative_id 					: Foreign Key From Document, Integer, Required
 *	created_at						: Timestamp
 * 	updated_at						: Timestamp
 * 	deleted_at						: Timestamp
 * 
/* ----------------------------------------------------------------------
 * Document Relationship :

 * ---------------------------------------------------------------------- */

use Str, Validator, DateTime, Exception;

class Relative extends BaseModel {
	use \ThunderID\Person\Models\Relations\BelongsTo\HasOrganisationTrait;
	
	public 		$timestamps 		= true;

	protected 	$table 				= 'relatives';
	
	public $searchable 				= 	[
											'id' 						=> 'ID', 
											'organisationid' 			=> 'OrganisationID', 
											'relativeid' 				=> 'RelativeID', 
											'personid' 					=> 'PersonID', 
											'withattributes' 			=> 'WithAttributes'
										];
	public $sortable 				= ['created_at'];


	/* ---------------------------------------------------------------------------- CONSTRUCT ----------------------------------------------------------------------------*/
	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/
	static function boot()
	{
		parent::boot();

		Static::saving(function($data)
		{
			$validator = Validator::make($data->toArray(), $data->rules);

			if ($validator->passes())
			{
				return true;
			}
			else
			{
				$data->errors = $validator->errors();
				return false;
			}
		});
	}

	/* ---------------------------------------------------------------------------- ERRORS ----------------------------------------------------------------------------*/
	/**
	 * return errors
	 *
	 * @return MessageBag
	 * @author 
	 **/
	function getError()
	{
		return $this->errors;
	}

	/* ---------------------------------------------------------------------------- QUERY BUILDER ---------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- MUTATOR ---------------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- ACCESSOR --------------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- FUNCTIONS -------------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- SCOPE -------------------------------------------------------------------------------*/
	public function scopeID($query, $variable)
	{
		return $query->where('id', $variable);
	}

	public function scopeOrganisationID($query, $variable)
	{
		return $query->where('organisation_id', $variable);
	}

	public function scopeRelativeID($query, $variable)
	{
		return $query->where('relative_id', $variable);
	}

	public function scopePersonID($query, $variable)
	{
		return $query->where('person_id', $variable);
	}

	public function scopeWithAttributes($query, $variable)
	{
		if(!is_array($variable))
		{
			$variable 			= [$variable];
		}
		return $query->with($variable);
	}
}
