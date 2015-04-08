<?php namespace ThunderID\Person\Controllers;

use \App\Http\Controllers\Controller;
use \ThunderID\Person\Models\Person;
use \ThunderID\Commoquent\Getting;
use \ThunderID\Commoquent\Saving;
use \ThunderID\Commoquent\Deleting;
use Input;

class PersonController extends Controller {

	public function __construct()
	{
		//
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	function index($page = 1)
	{
		$per_page 								= 12;
	
		$contents 								= $this->dispatch(new Getting(new Person, Input::get('search'), Input::get('sort') ,(int)$page, $per_page));
		
		return $contents;
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store($id = null, $attributes = null)
	{
		$content 								= $this->dispatch(new Saving(new Person, $attributes, $id));

		return $content;
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$content 						= $this->dispatch(new Getting(new Person,['ID' => $id, 'WithAttributes' => ['contacts', 'relatives', 'works', 'works.branch', 'works.branch.organisation', 'documents']], ['created_at' => 'asc'] ,1, 1));
		
		return $content;
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, $attributes = null)
	{
		$content 							= $this->dispatch(new Saving(new Person, $attributes, $id));

		return $content;
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$content 						= $this->dispatch(new Deleting(new Person, $id));
	
		return $content;
	}
}