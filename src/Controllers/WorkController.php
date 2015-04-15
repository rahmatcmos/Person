<?php namespace ThunderID\Person\Controllers;

use \App\Http\Controllers\Controller;
use \ThunderID\Person\Models\Person;
use \ThunderID\Work\Models\Work;
use \ThunderID\Commoquent\Getting;
use \ThunderID\Commoquent\Saving;
use \ThunderID\Commoquent\Deleting;
use Input, Hash, DB;

class WorkController extends Controller {

	public function __construct()
	{
		//
	}

	/**
	 * Display the all resources with weak entitites.
	 *
	 * @param  int  $id
	 * @return Response
	 */

	public function index($person_id, $page = 1)
	{
		$per_page 								= 12;
	
		$search 								= Input::get('search');
		$search['PersonID']						= $person_id;

		$contents 								= $this->dispatch(new Getting(new Work, $search, Input::get('sort') ,(int)$page, $per_page));

		return $contents;
	}

	/**
	 * Display the specified resource with weak entitites.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($person_id, $id)
	{
		$search['PersonID']						= $person_id;
		$search['ID']							= $id;
		$search['WithAttributes']				= ['organisationchart', 'organisationchart.branch'];

		$contents 								= $this->dispatch(new Getting(new Work, $search, ['created_at' => 'desc'] ,1, 1));
		
		return $contents;
	}
}