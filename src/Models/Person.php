<?php namespace ThunderID\Person\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

/* ----------------------------------------------------------------------
 * Document Model:
 * 	ID 								: Auto Increment, Integer, PK
 * 	name 	 						: Varchar, 255, Required
 * 	prefix_title 					: Varchar, 255, Required
 * 	suffix_title 					: Varchar, 255, Required
 * 	place_of_birth 					: Varchar, 255, Required
 * 	date_of_birth 					: Date, Y-m-d, Required
 * 	gender 							: Enum Female or Male, Required
 *	password						: Varchar, 255
 *	avatar							: 
 *	created_at						: Timestamp
 * 	updated_at						: Timestamp
 * 	deleted_at						: Timestamp
 * ---------------------------------------------------------------------- */

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

	use SoftDeletes;
	use \ThunderID\Person\Models\Relations\BelongsToMany\HasRelativesTrait;
	use \ThunderID\Person\Models\Relations\BelongsToMany\HasDocumentsTrait;
	use \ThunderID\Person\Models\Relations\BelongsToMany\HasWorksTrait;
	use \ThunderID\Person\Models\Relations\MorphMany\HasContactsTrait;
	use \ThunderID\Person\Models\Relations\HasMany\HasWidgetsTrait;

	public 		$timestamps 		= true;

	protected 	$table 				= 'persons';
	protected 	$fillable			= [
										'name' 							,
										'prefix_title' 					,
										'suffix_title' 					,
										'place_of_birth' 				,
										'date_of_birth' 				,
										'gender' 						,
										'password'						,
										'avatar'						,
									];
	protected	$dates 				= ['created_at', 'updated_at', 'deleted_at'];
	protected 	$rules				= [
										'name' 							=> 'required|max:255',
										'prefix_title' 					=> 'max:255',
										'suffix_title' 					=> 'max:255',
										'place_of_birth' 				=> 'required|max:255',
										'date_of_birth' 				=> 'required|date_format:"Y-m-d"',
										'gender' 						=> 'required|in:female,male',
										'password'						=> 'max:255',
									];
	public $searchable 				= 	[
											'id' 						=> 'ID', 
											'fullname' 					=> 'FullName', 
											'prefixtitle' 				=> 'PrefixTitle', 
											'suffixtitle' 				=> 'SuffixTitle', 
											'dateofbirth' 				=> 'DateOfBirth', 
											'gender' 					=> 'Gender', 
											'withattributes' 			=> 'WithAttributes',
											'currentwork' 				=> 'CurrentWork',
											'currentworkon' 			=> 'CurrentWorkOn',
											'currentcontact' 			=> 'CurrentContact',
											'email'			 			=> 'Email',
											'defaultemail' 				=> 'DefaultEmail',
											'experiences' 				=> 'Experiences',
											'checkrelation' 			=> 'CheckRelation',
											'checkwork'	 				=> 'CheckWork',
											'checkresign'	 			=> 'CheckResign',
											'checkwidget'	 			=> 'CheckWidget',
											'checkapps'	 				=> 'CheckApps',
											'checkcreate' 				=> 'CheckCreate',
											'checkrelative' 			=> 'CheckRelative',
											'groupcontacts' 			=> 'GroupContacts',
											'requireddocuments'	 		=> 'RequiredDocuments',
										];
	public $sortable 				= ['name', 'prefix_title', 'suffix_title', 'date_of_birth', 'created_at', 'persons.created_at'];
	
	protected $appends				= ['has_relatives', 'has_works', 'has_contacts'];

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

	public function getHasRelativesAttribute($value)
	{
		// dd($this->getRelations());exit;
		if(isset($this->getRelations()['relatives']) && count($this->getRelations()['relatives']))
		{
			return true;
		}
		return false;
	}

	public function getHasWorksAttribute($value)
	{
		if(isset($this->getRelations()['works']) && count($this->getRelations()['works']))
		{
			return true;
		}
		return false;
	}

	public function getHasContactsAttribute($value)
	{
		if(isset($this->getRelations()['contacts']) && count($this->getRelations()['contacts']))
		{
			return true;
		}
		return false;
	}
	
	/* ---------------------------------------------------------------------------- FUNCTIONS -------------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- SCOPE -------------------------------------------------------------------------------*/

	public function scopeID($query, $variable)
	{
		return $query->where('persons.id', $variable);
	}

	public function scopeFullName($query, $variable)
	{
		return $query->where('name', 'like' ,'%'.$variable.'%');
	}

	public function scopePrefixTitle($query, $variable)
	{
		return $query->where('prefix_title', 'like' ,'%'.$variable.'%');
	}

	public function scopeSuffixTitle($query, $variable)
	{
		return $query->where('suffix_title', 'like' ,'%'.$variable.'%');
	}

	public function scopeOrPrefixTitle($query, $variable)
	{
		return $query->orwhere('prefix_title', 'like' ,'%'.$variable.'%');
	}

	public function scopeOrSuffixTitle($query, $variable)
	{
		return $query->orwhere('suffix_title', 'like' ,'%'.$variable.'%');
	}

	public function scopeDateOfBirth($query, $variable)
	{
		if(is_array($variable) && count($variable)==2)
		{
			return $query->where('date_of_birth', '>' , $variable[0])->where('date_of_birth', '<=' , $variable[1]);
		}

		return $query->where('date_of_birth', '>' , $variable);
	}

	public function scopeGender($query, $variable)
	{
		return $query->where('gender', $variable);
	}


	public function scopeCheckCreate($query, $variable)
	{
		if(!is_array($variable))
		{
			$days 				= new DateTime($variable);

			return $query->where('created_at', '>=', $days->format('Y-m-d'));
		}
		return $query->where('created_at', '>=', $variable[0])
					->where('created_at', '<=', $variable[1]);
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
