<?php namespace ThunderID\Person\Controllers;

use \App\Http\Controllers\Controller;
use \ThunderID\Person\Models\Person;
use \ThunderID\Person\Models\Relative;
use \ThunderID\Doclate\Models\PersonDocument;
use \ThunderID\Doclate\Models\DocumentDetail;
use \ThunderID\Work\Models\Work;
use \ThunderID\Organisation\Models\Organisation;
use \ThunderID\Schedule\Models\PersonSchedule;
use \ThunderID\Contact\Models\Contact;
use \ThunderID\Commoquent\Getting;
use \ThunderID\Commoquent\Saving;
use \ThunderID\Commoquent\Deleting;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

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
	public function index($page = 1, $search = null, $sort = null, $per_page = 12)
	{
		$contents 								= $this->dispatch(new Getting(new Person, $search,  $sort,(int)$page, $per_page));

		return $contents;
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store($id = null, $attributes=null)
	{
		if(isset($attributes['person']['password']))
		{
			$attributes['person']['password']	= Hash::make($attributes['person']['password']);
		}

		DB::beginTransaction();
		
		$content 								= $this->dispatch(new Saving(new Person, $attributes['person'], $id, new Organisation, $attributes['organisation']['id']));

		$is_success 							= json_decode($content);
		if(!$is_success->meta->success)
		{
			DB::rollback();
			return $content;
		}

		if(isset($attributes['works']))
		{
			foreach ($attributes['works'] as $key => $value) 
			{
				if(isset($value['id']) && $value['id']!='' && !is_null($value['id']))
				{
					$work_id					= $value['id'];
				}
				else
				{
					$work_id					= null;
				}
				$saved_work 					= $this->dispatch(new Saving(new Work, $value, $work_id, new Person, $is_success->data->id));
				$is_success_2 					= json_decode($saved_work);
				if(!$is_success_2->meta->success)
				{
					DB::rollback();
					return $saved_work;
				}
			}
		}

		if(isset($attributes['documents']))
		{
			foreach ($attributes['documents'] as $key => $value) 
			{
				$attributes['document_id']		= $value['document']['document_id'];
				if(isset($value['document']['id']) && $value['document']['id']!='' && !is_null($value['document']['id']))
				{
					$attributes['id']			= $value['document']['id'];
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
				foreach ($attributes['documents'][$key]['details'] as $key2 => $value2) 
				{
					$attributes_2['template_id']	= $value2['template_id'];
					if((int)($value2['value']))
					{
						$attributes_2['numeric'] = $value2['value'];
					}
					else
					{
						$attributes_2['text']			= $value2['value'];
					}
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

		if(isset($attributes['relatives']))
		{
			foreach ($attributes['relatives'] as $key => $value) 
			{
				if(isset($value['id']))
				{
					$saved_relative 		= $this->dispatch(new Saving(new Person, $value, $value['id'], new Person, $is_success->data->id, ['relationship' => $value['relationship']]));
				}
				else
				{
					$saved_relative 		= $this->dispatch(new Saving(new Person, $value, null, new Person, $is_success->data->id, ['relationship' => $value['relationship']]));
				}

				$is_success_2 				= json_decode($saved_relative);
				if(!$is_success_2->meta->success)
				{
					DB::rollback();
					return $saved_relative;
				}
				
				if(isset($value['contacts']))
				{
					foreach ($value['contacts'] as $key2 => $value2) 
					{
						$contact['item']			= $value2['item'];
						$contact['value']			= $value2['value'];
						if($key==count($value['contacts'])-1)
						{
							$contact['is_default']	= true;
						}

						if(isset($value2['id']) && $value2['id']!='' && !is_null($value2['id']))
						{
							$contact['id']			= $value2['id'];
						}
						else
						{
							$contact['id']			= null;
						}

						$saved_contact 				= $this->dispatch(new Saving(new Contact, $contact, $contact['id'], new Person, $is_success_2->data->id));					
						$is_success_3 				= json_decode($saved_contact);
						if(!$is_success_3->meta->success)
						{
							DB::rollback();
							return $saved_contact;
						}
					}
				}
			}
		}

		if(isset($attributes['contacts']))
		{
			foreach ($attributes['contacts'] as $key0 => $value0) 
			{
				foreach ($attributes['contacts'][$key0] as $key => $value) 
				{
					$contact					= $value;
					
					if(isset($value['id']) && $value['id']!='' && !is_null($value['id']))
					{
						$contact['id']			= $value['id'];
					}
					else
					{
						$contact['id']			= null;
					}

					$saved_contact 				= $this->dispatch(new Saving(new Contact, $contact, $contact['id'], new Person, $is_success->data->id));

					$is_success_2 				= json_decode($saved_contact);
					if(!$is_success_2->meta->success)
					{
						DB::rollback();
						return $saved_contact;
					}
				}
			}
		}

		if(isset($attributes['schedules']))
		{
			foreach ($attributes['schedules'] as $key => $value) 
			{
				$schedule					= $value;
				
				if(isset($value['id']) && $value['id']!='' && !is_null($value['id']))
				{
					$schedule['id']			= $value['id'];
				}
				else
				{
					$schedule['id']			= null;
				}

				$saved_schedule 			= $this->dispatch(new Saving(new PersonSchedule, $schedule, $schedule['id'], new Person, $is_success->data->id));

				$is_success_2 				= json_decode($saved_schedule);
				if(!$is_success_2->meta->success)
				{
					DB::rollback();
					return $saved_schedule;
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
		$content 								= $this->dispatch(new Getting(new Person,['ID' => $id, 'CurrentWork' => 'updated_at', 'CurrentContact' => 'item', 'Experiences' => 'created_at', 'requireddocuments' => 'documents.created_at', 'groupcontacts' => '', 'checkrelative' => ''], ['created_at' => 'asc'] ,1, 1));
		
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

	public function relativedestroy($person_id, $id)
	{
		$content 							= $this->dispatch(new Getting(new Relative,['personid' => $person_id], ['created_at' => 'asc'] ,1, 1));

		$result 							= json_decode($content);
		
		if($result->meta->success)
		{
			$content 						= $this->dispatch(new Deleting(new Relative, $result->data->id));
		} 						
	
		return $content;
	}
}
