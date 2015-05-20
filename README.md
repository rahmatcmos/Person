# Person Overview

Package contain migrations, seeds and models for Person (HR). Documentation will be written in wiki.

# Installation

composer.json:
```
	"thunderid/person": "dev-master"
```

run
```
	composer update
```

```
	composer dump-autoload
```

# Usage

service provider
```
'ThunderID\Person\PersonServiceProvider'
```

migration
```
  php artisan migrate --path=vendor/thunderid/person/src/migrations
```

seed (run in mac or linux)
```
  php artisan db:seed --class=ThunderID\\Person\\seeds\\DatabaseSeeder
```

seed (run in windows)
```
  php artisan db:seed --class='\ThunderID\Person\seeds\DatabaseSeeder'
```
# Developer Notes for UI
## Table Person

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
	3 Relationships belongsToMany 
	{
		Documents
		Works
		Calendars
	}

	2 Relationships hasMany 
	{
		Widgets
		Schedules
	}

	1 Relationship morphMany 
	{
		Contacts
	}

	1 Relationship hasOne 
	{
		Finger
	}

/* ----------------------------------------------------------------------
 * Document Fillable :
 * 	first_name
	middle_name
	last_name
	nick_name
	full_name
	prefix_title
	suffix_title
	place_of_birth
	date_of_birth
	gender
	username
	password
	avatar

/* ----------------------------------------------------------------------
 * Document Observe :
 	delete 							: cannot delete person has contacts or relatives or works

/* ----------------------------------------------------------------------
 * Document Searchable :
 * 	id 								: Search by id, parameter => string, id
  	fullname 						: Search by fullname, parameter => string, fullname
  	prefixtitle 					: Search by prefixtitle, parameter => string, prefixtitle
  	suffixtitle 					: Search by suffixtitle, parameter => string, suffixtitle
  	dateofbirth 					: Search by dateofbirth, parameter => string, dateofbirth
  	gender 							: Search by gender, parameter => string, gender
	withattributes					: Search with relationship, parameter => array of relationship (ex : ['relatives'], if relationship is belongsTo then return must be single object, if hasMany or belongsToMany then return must be plural object)
  	currentwork 					: With active works and branch organisation and applications
  	currentcontact 					: With default contacts of each type
  	email 							: Where has email, parameter => email
  	takenworkleave 					: With special schedule of workleave, parameter : status and ondate
  	checktakenworkleave				: Check takenworkleave, same parameter as takenworkleave
  	defaultemail 					: Get default email
  	experiences 					: With previous experiences and organisation
  	checkrelation 					: With relatives, parameter : relative_id
  	checkwork 						: With works that start after some days, parameter : start date (in english context)
  	checkwidget 					: Where has widgets that belongsto person
  	checkapps						: With application allowed for user, 
  	checkresign 					: With works that end after some days, parameter : end date (in english context)
  	checkwidget 					: With widgets, parameter : order
  	checkcreate 					: Search by created_at after some days, parameter : created at (in english context)
  	checkrelative 					: Take only one relative
  	checkworkleave 					: Check person workleave in time, paramater : on date
  	groupcontacts 					: With contacts group by item
  	branchname 						: Where branch name is like something
  	charttag 						: Where chart tag is like something
  	fullschedule 					: Looking for report in single date, parameter -> date on
  	quotas 							: Looking for report of default workleave qoutas in date range, parameter array -> date ondate
  	minusquotas 					: Looking for report of additional workleave qoutas in date range, parameter array -> date ondate
  	workleaveid 					: with person workleave has id, parameter workleave id
  	requireddocuments 				: With required documents and templates, parameter : order

/* ----------------------------------------------------------------------

## Table Relative

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
	//this package
 	1 Relationship belongsTo 
	{
		Person
	}
	//other package
 	1 Relationship belongsTo 
	{
		Organisation
	}
/* ----------------------------------------------------------------------
 * Document Searchable :
 * 	id 								: Search by id, parameter => string, id
	organisationid 					: Search by organisation id, parameter => string, organisation_id
	relativeid 						: Search by relative id, parameter => string, relative_id
	personid 						: Search by person_id, parameter => string, person_id
	withattributes					: Search with relationship, parameter => array of relationship (ex : ['relatives'], if relationship is belongsTo then return must be single object, if hasMany or belongsToMany then return must be plural object)

/* ----------------------------------------------------------------------