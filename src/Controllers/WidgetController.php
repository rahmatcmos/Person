<?php namespace ThunderID\Person\Controllers;

use \App\Http\Controllers\Controller;
use \ThunderID\Person\Models\Person;
use \ThunderID\Widboard\Models\PersonWidget;
use \ThunderID\Commoquent\Getting;
use \ThunderID\Commoquent\Saving;
use \ThunderID\Commoquent\Deleting;
use Input;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class WidgetController extends Controller {

	public function __construct()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store($personid)
	{
		$personid 								= Input::get('attributes')['person']['id'];
		$attributes 							= Input::get('attributes')['widget'];

		DB::beginTransaction();
		
		$content 								= $this->dispatch(new Saving(new PersonWidget, $attributes, null, new Person, $personid));

		$is_success 							= json_decode($content);
		if(!$is_success->meta->success)
		{
			DB::rollback();
			return $content;
		}

		DB::commit();

		return $content;

	}
	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($person_id, $id)
	{
		$content 							= $this->dispatch(new Getting(new PersonWidget,['personid' => $person_id, 'ID' => $id], ['created_at' => 'asc'] ,1, 1));
		$result 							= json_decode($content);
		
		if($result->meta->success)
		{
			$content 						= $this->dispatch(new Deleting(new PersonWidget, $id));
		} 						
	
		return $content;
	}
}