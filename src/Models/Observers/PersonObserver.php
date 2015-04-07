<?php namespace ThunderID\Person\Models\Observers;

use \Validator;

/* ----------------------------------------------------------------------
 * Event:
 * 	Creating						
 * 	Saving						
 * 	Updating						
 * 	Deleting						
 * ---------------------------------------------------------------------- */

class PersonObserver 
{
	public function creating($model)
	{
		//
	}

	public function saving($model)
	{
		$validator 				= Validator::make($model['attributes'], $model['rules']);

		if ($validator->passes())
		{
			return true;
		}
		else
		{
			$model['errors'] 	= $validator->errors();

			return false;
		}
	}

	public function updating($model)
	{
		//
	}

	public function deleting($model)
	{
		//
		if($model->relatives)
		{
			$model['errors'] 	= ['Cannot delete model has relatives'];

			return false;
		}

		if($model->works)
		{
			$model['errors'] 	= ['Cannot delete model has works'];

			return false;
		}

		return true;
	}
}
