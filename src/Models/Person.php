<?php namespace ThunderID\Person\Models;

/* ----------------------------------------------------------------------
 * Document Model:
 * 	ID 								: Auto Increment, Integer, PK
 * 	first_name 						: Varchar, 255, Required
 * 	middle_name 					: Varchar, 255
 * 	last_name 						: Varchar, 255
 * 	nick_name 						: Varchar, 255, Required
 * 	prefix_title 					: Varchar, 255, Required
 * 	suffix_title 					: Varchar, 255, Required
 * 	place_of_birth 					: Varchar, 255, Required
 * 	date_of_birth 					: Date, Y-m-d, Required
 * 	gender 							: Enum Female or Male, Required
 *	marital_status					: Enum Single or Married or Divorced or Widowed, Required
 *	nationality						: Varchar, 255, Required
 *	created_at						: Timestamp
 * 	updated_at						: Timestamp
 * 	deleted_at						: Timestamp
 * 
/* ----------------------------------------------------------------------
 * Document Relationship :
 * 	//this package
 	1 Relationship belongsToMany 
	{
		Relatives
	}

	//other package
	2 Relationships belongsToMany 
	{
		Documents
		Works
	}

	1 Relationship morphMany 
	{
		Contacts
	}

 * ---------------------------------------------------------------------- */

use Str, Validator, DateTime, Exception;

class Person extends BaseModel {

	public 		$timestamps 		= true;

	protected 	$table 				= 'persons';
	protected 	$fillable			= [
										'first_name' 					,
										'middle_name' 					,
										'last_name' 					,
										'nick_name' 					,
										'prefix_title' 					,
										'suffix_title' 					,
										'place_of_birth' 				,
										'date_of_birth' 				,
										'gender' 						,
										'marital_status'				,
										'nationality'					,
									];
	protected	$dates 				= ['created_at', 'updated_at', 'deleted_at'];
	protected 	$rules				= [
										'first_name' 					=> 'required|max:255',
										'middle_name' 					=> 'max:255',
										'last_name' 					=> 'max:255',
										'nick_name' 					=> 'required|max:255',
										'prefix_title' 					=> 'required|max:255',
										'suffix_title' 					=> 'required|max:255',
										'place_of_birth' 				=> 'required|max:255',
										'date_of_birth' 				=> 'required|date_format:"Y-m-d"',
										'gender' 						=> 'required|in:female,male',
										'marital_status'				=> 'required|in:single,married,divorced,widowed',
										'nationality'					=> 'required|max:255',
									];

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

	/* ------------------------------------------------------------------- RELATIONSHIP IN PERSON PACKAGE -------------------------------------------------------------------*/

	public function Relatives()
	{
		return $this->belongsToMany('ThunderID\Person\Models\Person', 'relatives', 'person_id', 'relative_id')
				->withPivot('relationship','occupation');
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN DOCUMENT PACKAGE -------------------------------------------------------------------*/
	public function Documents()
	{
		return $this->belongsToMany('ThunderID\Document\Models\Document', 'persons_documents', 'person_id', 'document_id');
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN WORK PACKAGE -------------------------------------------------------------------*/
	public function Works()
	{
		return $this->belongsToMany('ThunderID\Organisation\Models\OrganisationChart', 'works', 'person_id', 'organisation_chart_id');
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN CONTACT PACKAGE -------------------------------------------------------------------*/
	public function Contacts()
	{
		return $this->morphMany('ThunderID\Contact\Models\Contact');
	}

	/* ---------------------------------------------------------------------------- QUERY BUILDER ---------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- MUTATOR ---------------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- ACCESSOR --------------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- FUNCTIONS -------------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- SCOPE -------------------------------------------------------------------------------*/
}
