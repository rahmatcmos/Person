<?php namespace ThunderID\Person\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

/* ----------------------------------------------------------------------
 * Document Model:
 * 	ID 								: Auto Increment, Integer, PK
 * 	first_name 						: Varchar, 255, Required
 * 	middle_name 					: Varchar, 255
 * 	last_name 						: Varchar, 255
 * 	nick_name 						: Varchar, 255, Required
 * 	full_name 						: Varchar, 255
 * 	prefix_title 					: Varchar, 255, Required
 * 	suffix_title 					: Varchar, 255, Required
 * 	place_of_birth 					: Varchar, 255, Required
 * 	date_of_birth 					: Date, Y-m-d, Required
 * 	gender 							: Enum Female or Male, Required
 *	username						: Varchar, 255
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
										'first_name' 					,
										'middle_name' 					,
										'last_name' 					,
										'nick_name' 					,
										'full_name' 					,
										'prefix_title' 					,
										'suffix_title' 					,
										'place_of_birth' 				,
										'date_of_birth' 				,
										'gender' 						,
										'username'						,
										'password'						,
										'avatar'						,
									];
	protected	$dates 				= ['created_at', 'updated_at', 'deleted_at'];
	protected 	$rules				= [
										'first_name' 					=> 'required|max:255',
										'middle_name' 					=> 'max:255',
										'last_name' 					=> 'max:255',
										'nick_name' 					=> 'required|max:255',
										'full_name' 					=> 'max:255',
										'prefix_title' 					=> 'max:255',
										'suffix_title' 					=> 'max:255',
										'place_of_birth' 				=> 'required|max:255',
										'date_of_birth' 				=> 'required|date_format:"Y-m-d"',
										'gender' 						=> 'required|in:female,male',
										'username'						=> 'max:255',
										'password'						=> 'max:255',
									];
	public $searchable 				= 	[
											'id' 						=> 'ID', 
											'firstname' 				=> 'FirstName', 
											'lastname' 					=> 'LastName', 
											'fullname' 					=> 'FullName', 
											'prefixtitle' 				=> 'PrefixTitle', 
											'suffixtitle' 				=> 'SuffixTitle', 
											'orlastname' 				=> 'OrLastName', 
											'orprefixtitle' 			=> 'OrPrefixTitle', 
											'orsuffixtitle' 			=> 'OrSuffixTitle', 
											'dateofbirth' 				=> 'DateOfBirth', 
											'gender' 					=> 'Gender', 
											'withattributes' 			=> 'WithAttributes',
											'currentwork' 				=> 'CurrentWork',
											'currentworkon' 			=> 'CurrentWorkOn',
											'currentcontact' 			=> 'CurrentContact',
											'experiences' 				=> 'Experiences',
											'checkrelation' 			=> 'CheckRelation',
											'checkwork'	 				=> 'CheckWork',
											'checkresign'	 			=> 'CheckResign',
											'checkwidget'	 			=> 'CheckWidget',
											'checkcreate' 				=> 'CheckCreate',
											'requireddocuments'	 		=> 'RequiredDocuments',
										];
	public $sortable 				= ['first_name', 'last_name', 'prefix_title', 'suffix_title', 'date_of_birth', 'created_at'];

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

	public function scopeFirstName($query, $variable)
	{
		return $query->where('first_name', 'like' ,'%'.$variable.'%');
	}

	public function scopeLastName($query, $variable)
	{
		return $query->where('last_name', 'like' ,'%'.$variable.'%');
	}

	public function scopeFullName($query, $variable)
	{
		return $query->where('full_name', 'like' ,'%'.$variable.'%');
	}

	public function scopePrefixTitle($query, $variable)
	{
		return $query->where('prefix_title', 'like' ,'%'.$variable.'%');
	}

	public function scopeSuffixTitle($query, $variable)
	{
		return $query->where('suffix_title', 'like' ,'%'.$variable.'%');
	}

	public function scopeOrLastName($query, $variable)
	{
		return $query->orwhere('last_name', 'like' ,'%'.$variable.'%');
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
