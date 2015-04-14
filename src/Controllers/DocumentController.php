<?php namespace ThunderID\Person\Controllers;

use \App\Http\Controllers\Controller;
use \ThunderID\Person\Models\Person;
use \ThunderID\Doclate\Models\PersonDocument;
use \ThunderID\Doclate\Models\DocumentDetail;
use \ThunderID\Work\Models\Work;
use \ThunderID\Contact\Models\Contact;
use \ThunderID\Commoquent\Getting;
use \ThunderID\Commoquent\Saving;
use \ThunderID\Commoquent\Deleting;
use Input, Hash, DB;

class DocumentController extends Controller {

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

		$contents 								= $this->dispatch(new Getting(new PersonDocument, $search, Input::get('sort') ,(int)$page, $per_page));

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
		$search['WithAttributes']				= ['details', 'details.template', 'document', 'person'];

		$contents 								= $this->dispatch(new Getting(new PersonDocument, $search, ['created_at' => 'desc'] ,1, 1));
		
		return $contents;
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($person_id, $id)
	{
		$content 							= $this->dispatch(new Getting(new PersonDocument,['personid' => $person_id, 'ID' => $id], ['created_at' => 'asc'] ,1, 1));
		$result 							= json_decode($content);
		
		if($result->meta->success)
		{
			$content 						= $this->dispatch(new Deleting(new PersonDocument, $id));
		} 						
	
		return $content;
	}
}