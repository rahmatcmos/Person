<?php namespace ThunderID\Person\seeds;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use ThunderID\Person\Models\Person;
use Faker\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class PersonTableSeeder extends Seeder
{
	function run()
	{
		DB::table('persons')->truncate();
		$faker 										= Factory::create();
		$gender 									= ['male', 'female'];
		$prefix 									= ['Prof.', 'Dr.', 'Ir.'];
		$suffix 									= ['MT.', 'MSc.', 'BSc.', 'MSi.', 'BSi.', 'SE.', 'PhD.', 'SH.', 'SKom.', 'ST.', 'BA.'];
		try
		{
			foreach(range(1, 50) as $index)
			{
				$data = new Person;
				$data->fill([
					'name'							=> $faker->name,
					'prefix_title'					=> $prefix[rand(0,2)],
					'suffix_title'					=> $suffix[rand(0,10)],
					'place_of_birth'				=> $faker->city,
					'date_of_birth' 				=> $faker->date($format = 'Y-m-d', $max = 'now'), 
					'gender' 						=> $gender[rand ( 0 , 1 )],
					'password'						=> Hash::make('admin'),
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