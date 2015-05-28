<?php namespace ThunderID\Person\Controllers;

use \App\Http\Controllers\Controller;
use \ThunderID\Person\Models\Person;
use \ThunderID\Work\Models\Work;
use \ThunderID\Commoquent\Getting;
use \ThunderID\Commoquent\Saving;
use \ThunderID\Commoquent\Deleting;
use Input;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

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

	public function index($person_id, $page = 1, $search = null, $sort = null)
	{
		$per_page 								= 12;
	
		$search['PersonID']						= $person_id;

		$contents 								= $this->dispatch(new Getting(new Work, $search, $sort ,(int)$page, $per_page));

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
		$search['WithAttributes']				= ['chart', 'chart.branch'];

		$contents 								= $this->dispatch(new Getting(new Work, $search, ['created_at' => 'desc'] ,1, 1));
		
		return $contents;
	}

	public function delete($person_id, $id)
	{
		$content 								= $this->dispatch(new Getting(new Work,['personid' => $person_id, 'ID' => $id], ['created_at' => 'asc'] ,1, 1));
		$result 								= json_decode($content);
		
		if($result->meta->success && strtolower($result->data->status)!='admin')
		{
			$content 							= $this->dispatch(new Deleting(new Work, $id));
		}
		elseif($result->meta->success)
		{
			$works 							= json_decode(json_encode($result), true);
			$works['meta']['success'] 			=  	false;
			$works['meta']['errors'] 			= 'Tidak dapat menghapus admin.';
			$content 						= json_encode($works);
		}
		return $content;
	}
}
