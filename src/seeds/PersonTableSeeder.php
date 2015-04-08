<?php namespace ThunderID\Person\seeds;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use ThunderID\Person\Models\Person;
use \Faker;

class PersonTableSeeder extends Seeder
{
	function run()
	{
		$faker 										= Faker::create();
		$gender 									= ['male', 'female'];
		$marital_status 							= ['single', 'married', 'divorced', 'widowed'];
		$nationality								= ['WNI', 'WNA'];
		$prefix 									= ['Prof.', 'Dr.', 'Ir.'];
		$suffix 									= ['MT.', 'MSc.', 'BSc.', 'MSi.', 'BSi.', 'SE.', 'PhD.', 'SH.', 'SKom.', 'ST.', 'BA.'];
		try
		{
			foreach(range(1, 77) as $index)
			{
				$data = new Person;
				$data->fill([
					'first_name'					=>$faker->firstName,
					'middle_name'					=>$faker->firstName,
					'last_name'						=>$faker->lastName,
					'nick_name'						=>$faker->firstName,
					'prefix_title'					=>$prefix[rand(0,2)],
					'suffix_title'					=>$suffix[rand(0,10)],
					'place_of_birth'				=>$faker->city,
					'date_of_birth' 				=>$faker->date($format = 'Y-m-d', $max = 'now'), 
					'gender' 						=>$gender[rand ( 0 , 1 )],
					'marital_status'				=>$marital_status[rand ( 0 , 3 )],
					'nationality'					=>$nationality[rand(0,1)],
				]);

				if (!$data->save())
				{
					print_r($data->getError());
					exit;
				}
			}
		}
		catch (Exception $e) 
		{
    		echo 'Caught exception: ',  $e->getMessage(), "\n";
		}	
	}
}