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
 * Document Searchable :
 * 	id 								: Search by id, parameter => string, id
  	firstname 						: Search by firstname, parameter => string, firstname
  	lastname 						: Search by lastname, parameter => string, lastname
  	fullname 						: Search by fullname, parameter => string, fullname
  	prefixtitle 					: Search by prefixtitle, parameter => string, prefixtitle
  	suffixtitle 					: Search by suffixtitle, parameter => string, suffixtitle
  	orlastname 						: Search by or lastname, parameter => string, lastname
  	orprefixtitle 					: Search by or prefixtitle, parameter => string, prefixtitle
  	orsuffixtitle 					: Search by or suffixtitle, parameter => string, suffixtitle
  	dateofbirth 					: Search by dateofbirth, parameter => string, dateofbirth
	withattributes					: Search with relationship, parameter => array of relationship (ex : ['relatives'], if relationship is belongsTo then return must be single object, if hasMany or belongsToMany then return must be plural object)
  	currentwork 					: With active works and branch organisation and applications
  	currentcontact 					: With default contacts of each type
  	experiences 					: With previous experiences and organisation
  	checkrelation 					: With relatives, parameter : relative_id
  	checkwork 						: With works that start after some days, parameter : start date (in english context)
  	checkresign 					: With works that end after some days, parameter : end date (in english context)
  	checkwidget 					: With widgets, parameter : order
  	checkcreate 					: Search by created_at after some days, parameter : created at (in english context)
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