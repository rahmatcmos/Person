<?php namespace ThunderID\Person\Models\Observers;

use \Validator;

/* ----------------------------------------------------------------------
 * Event:
 * 	Saving						
 * 	Deleting						
 * ---------------------------------------------------------------------- */

class PersonObserver 
{
	public function saving($model)
	{
		$validator 				= Validator::make($model['attributes'], $model['rules'], ['uniqid.required' => 'ID tidak boleh kosong', 'uniqid.max' => 'Maksimal Panjang ID : 255 Karakter']);

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

	public function deleting($model)
	{
		//
		if($model->works->count() || $model->contacts->count() || $model->relatives->count())
		{
			$model['errors'] 	= ['Tidak dapat menghapus data personalia yang memiliki pekerjaan atau informasi kontak atau relasi dengan karyawan'];

			return false;
		}

		return true;
	}
}
