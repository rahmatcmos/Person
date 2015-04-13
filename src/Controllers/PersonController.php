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
	public function store()
	{
		$id 									= Input::get('attributes')['person']['id'];

		if(Input::has('attributes')['password'])
		{
			$attributes['password']				= Hash::make(Input::get('attributes')['password']);
		}

		$id 									= Input::get('id');
		$attributes 							= Input::get('attributes')['person'];

		DB::beginTransaction();
		
		$content 								= $this->dispatch(new Saving(new Person, $attributes, $id));

		$is_success 							= json_decode($content);
		if(!$is_success->meta->success)
		{
			DB::rollback();
			return $content;
		}

		if(isset(Input::get('attributes')['works']))
		{
			foreach (Input::get('attributes')['works'] as $key => $value) 
			{
				$saved_work 				= $this->dispatch(new Saving(new Work, $value, null, new Person, $is_success->data->id));
				$is_success_2 				= json_decode($saved_work);
				if(!$is_success_2->meta->success)
				{
					DB::rollback();
					return $saved_work;
				}
			}
		}

		if(isset(Input::get('attributes')['documents']))
		{
			foreach (Input::get('attributes')['documents'] as $key => $value) 
			{
				$attributes['document_id']	= $value['document']['id'];
				if(isset($value['id']) && $value['id']!='' && !is_null($value['id']))
				{
					$attributes['id']			= $value['id'];
				}
				else
				{
					$attributes['id']			= null;
				}
				$saved_document 				= $this->dispatch(new Saving(new PersonDocument, $attributes, $attributes['id'], new Person, $is_success->data->id));
				$is_success_2 					= json_decode($saved_document);
				if(!$is_success_2->meta->success)
				{
					DB::rollback();
					return $saved_document;
				}
				foreach (Input::get('attributes')['documents'][$key]['details'] as $key2 => $value2) 
				{
					$attributes_2['document_template_id']	= $value2['document_template_id'];
					$attributes_2['value']		= $value2['value'];
					if(isset($value2['id']) && $value2['id']!='' && !is_null($value2['id']))
					{
						$attributes_2['id']		= $value2['id'];
					}
					else
					{
						$attributes_2['id']		= null;
					}

					$saved_detail 				= $this->dispatch(new Saving(new DocumentDetail, $attributes_2, $attributes_2['id'], new PersonDocument, $is_success_2->data->id));
					$is_success_3 				= json_decode($saved_detail);
					if(!$is_success_3->meta->success)
					{
						DB::rollback();
						return $saved_detail;
					}	
				}
			}
		}

		if(isset(Input::get('attributes')['relatives']))
		{
			foreach (Input::get('attributes')['relatives'] as $key => $value) 
			{
				if(isset($value['id']))
				{
					$saved_relative 		= $this->dispatch(new Saving(new Person, $value, $value['id'], new Person, $is_success->data->id, $value['relationship']));
				}
				else
				{
					$saved_relative 		= $this->dispatch(new Saving(new Person, $value, null, new Person, $is_success->data->id, $value['relationship']));
				}
				$is_success_2 				= json_decode($saved_relative);
				if(!$is_success_2->meta->success)
				{
					DB::rollback();
					return $saved_relative;
				}
			}
		}

		if(isset(Input::get('attributes')['contact']))
		{
			foreach (Input::get('attributes')['contact'] as $key0 => $value0) 
			{
				foreach (Input::get('attributes')['contact'][$key0] as $key => $value) 
				{
					$i 								= 0;
					if($value!='')
					{
						$contact['item']			= $key0;
						$contact['value']			= $value;
						if($i==0)
						{
							$contact['is_default']	= true;
							$i 						= 1;
						}
						$saved_contact 				= $this->dispatch(new Saving(new Contact, $contact, null, new Person, $is_success->data->id));
						$is_success_2 				= json_decode($saved_contact);
						if(!$is_success_2->meta->success)
						{
							DB::rollback();
							return $saved_contact;
						}
					}
				}
			}
		}

		DB::commit();

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
		$content 								= $this->dispatch(new Getting(new Person,['ID' => $id, 'CurrentWork' => 'updated_at', 'CurrentContact' => 'item', 'Experiences' => 'created_at', 'requireddocuments' => 'documents.created_at'], ['created_at' => 'asc'] ,1, 1));
		
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
		$content 								= $this->dispatch(new Saving(new Person, $attributes, $id));

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
		$content 								= $this->dispatch(new Deleting(new Person, $id));
	
		return $content;
	}


	/**
	 * Display the specified resource with weak entitites.
	 *
	 * @param  int  $id
	 * @return Response
	 */

	public function documents($person_id, $id, $page = 1)
	{
		$per_page 								= 12;
	
		$search 								= Input::get('search');
		$search['Person']						= $person_id;
		$search['Document']						= $id;
		$contents 								= $this->dispatch(new Getting(new PersonDocument, $search, Input::get('sort') ,(int)$page, $per_page));

		return $contents;
	}

	public function document($person_id, $doc_id, $id)
	{
		$search['Person']						= $person_id;
		$search['Document']						= $doc_id;
		$search['ID']							= $id;

		$contents 								= $this->dispatch(new Getting(new PersonDocument, $search, ['created_at' => 'desc'] ,1, 1));
		
		return $contents;
	}

	public function works($person_id, $page = 1)
	{
		$per_page 								= 12;
	
		$search 								= Input::get('search');
		$search['Person']						= $person_id;

		$contents 								= $this->dispatch(new Getting(new Work, $search, Input::get('sort') ,(int)$page, $per_page));
		
		return $contents;
	}

	public function contacts($person_id, $page = 1)
	{
		$per_page 								= 12;
	
		$search 								= Input::get('search');
		$search['Person']						= $person_id;

		$contents 								= $this->dispatch(new Getting(new Contact, $search, Input::get('sort') ,(int)$page, $per_page));
		
		return $contents;
	}
}