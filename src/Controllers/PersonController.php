<?php namespace ThunderID\Person\Controllers;

use \App\Http\Controllers\Controller;
use \ThunderID\Person\Models\Person;
use \ App\Commands\Getting;

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
		$per_page 								= 15;
	
		$contents 								= $this->dispatch(new Getting(new Person,['FirstName' => ''], ['created_at' => 'asc'] ,(int)$page, $per_page));
		
		return $contents;
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create($content = null)
	{
		//---------------------------------------------- GENERATE VIEW ----------------------------------------------
		$this->page->title 				= ucwords('Team');
		$this->page->subtitle 			= ($content ? 'Edit ' : 'Create a new ') . 'Team';
		$this->page->active_menu 		= $this->active_menu;
		$this->page->breadcrumb 		= $this->breadcrumb;
		if ($content)
		{
			$this->page->breadcrumb[] 	= ['link' => route('Cms.users.team.edit', ['_id' => $content['_id']]), 'label' => 'Edit ' . $content['_id']];
		}
		else
		{
			$this->page->breadcrumb[] 	= ['link' => route('Cms.users.team.show', ['_id' => $content['_id']]), 'label' => 'Create a new ' . str_singular('teams')];
		}


		return View::make('Cms::admin.pages.users.team.create')
					->with('page', $this->page)
					->with('me', $this->me)
					->with('data', $content);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store($content = null, $attributes = null)
	{
		$attributes 				= Input::only('name', 'username', 'email', 'role' );
		$attributes['type'] 		= 'team';
		$attributes['username'] 	= strtolower($attributes['username']);
		$attributes['extra_fields'] = Input::only('about','avatar');
		$attributes['extra_fields']['uploaded_images'] = json_decode(Input::get('uploaded_images'));
		for ($i = 0; $i < count(Input::get('thumbnail')); $i++ )
		{
			if (Input::get('thumbnail')[$i] || Input::get('thumbnail_desc')[$i] || Input::get('subtitle')[$i] || Input::get('content')[$i])
			{
				$thumbnail = json_decode(Input::get('thumbnail')[$i]);
				$attributes['extra_fields']['avatar'] = $thumbnail[0]->ori;
			}
		}

		//check username
		$contents 						= $this->dispatch(new GetUser(['username' => $attributes['username'], 'not' => (isset($content) ? $content['_id'] : '')], ['created_at' => 'desc'], 1, 1));
		$data 							= json_decode($contents);

		if($data->meta->success)
		{
			return Redirect::back()
					->withInput()
					->withErrors(['Username has been used']);
		}

		if (Input::get('active'))
		{
			$attributes['active'] = true;
		}
		else
		{
			$attributes['active'] = false;
		}

		if (Input::has('password'))
		{
			$attributes['password'] = Input::get('password');
			$attributes['password_confirmation'] = Input::get('password_confirmation');
		}

		if (isset($attributes['password']))
		{
			$rules['password'] = 'min:8|confirmed';
		}
		else
		{
			$rules 				= [];
		}

		$validator = Validator::make($attributes, $rules);
		if (!$validator->passes())
		{
			return Redirect::back()->withInput()->withErrors($validator);
		}
		unset($attributes['password_confirmation']);
		if ($content['_id'])
		{
			$data 	= $this->dispatch(new SaveUser($attributes, $content['_id']));
		}
		else
		{
			$data 	= $this->dispatch(new SaveUser($attributes, null));
		}
		$data 		= json_decode($data);
		if($data->meta->success == true)
		{
			return Redirect::route('Cms.users.team.index')
							->with('alert_success', 'User "' . $attributes['name'] . ' (@'.$attributes['username'].')' . '" has been ' . (!$data->data->_id ? ' created' : 'updated'));
		} 
		else
		{
			return Redirect::back()
							->withInput()
							->withErrors($data->meta->errors);
		}
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$contents 						= $this->dispatch(new GetUser(['_id' => $id, 'type' => 'team'], ['created_at' => 'desc'], 1, 1));
		$data 							= json_decode($contents);
		$articles 						= $this->dispatch(new GetArticle(['author' => $id], ['created_at' => 'desc'], 1, 10));
		$total_articles 				= $this->dispatch(new CountArticle(['author' => $id], ['created_at' => 'desc'], 1, 10));
		$articles 						= json_decode($articles);
		$total_articles 				= json_decode($total_articles);

		if($data->meta->success && $articles->meta->success && $total_articles->meta->success)
		{
			$user 							= json_decode(json_encode($data->data), true);
			// view
			$this->page->title 				= ucwords('Team');
			$this->page->subtitle 			= $data->data->name;
			$this->page->active_menu 		= $this->active_menu;
			$this->page->breadcrumb 		= $this->breadcrumb;
			$this->page->breadcrumb[] 		= ['link' => route('Cms.users.team.show', ['_id' => $id]), 'label' => $data->data->name];

			return View::make('Cms::admin.pages.users.team.show')
							->with('page', $this->page)
							->with('me', $this->me)
							->with('user', $user)
							->with('articles', $articles->data)
							->with('total_articles', $total_articles->data->total);
		}
		App::abort(404);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$contents 						= $this->dispatch(new GetUser(['_id' => $id, 'type' => 'team'], ['created_at' => 'desc'], 1, 1));
		$data 							= json_decode($contents);

		if($data->meta->success)
		{
			$content 				= json_decode(json_encode($data->data), true);
			return $this->create($content);
		}
		App::abort(404);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$contents 						= $this->dispatch(new GetUser(['_id' => $id, 'type' => 'team'], ['created_at' => 'desc'], 1, 1));
		$data 							= json_decode($contents);

		if($data->meta->success)
		{
			$content 			= json_decode(json_encode($data->data), true);
			return $this->store($content);
		}
		App::abort(404);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$contents 						= $this->dispatch(new GetUser(['_id' => $id, 'type' => 'team'], ['created_at' => 'desc'], 1, 1));
		$data 							= json_decode($contents);

		if(!$data->meta->success)
		{
			App::abort(404);
		}
		$user 							= json_decode(json_encode($data->data), true);

		if (Input::has('from_confirm_form'))
		{
			if (str_is('confirmed', strtolower(Input::get('submit'))))
			{
				$user['active'] 		= false;
				if($user['active']==false)
				{
					$user['active'] 	= true;
				}
				unset($user['_id']);
				unset($user['password']);
				unset($user['updated_at']);
				unset($user['created_at']);
				$content 	= $this->dispatch(new SaveUser($user, $id));
				$data 		= json_decode($content);
				if(!$data->meta->success)
				{
					return Redirect::route('Cms.users.team.show', ['id' => $id])->withErrors($this->user_service->getError());
				}
				else
				{
					return Redirect::route('Cms.users.team.index')->with('alert_success', ucwords('Team') . ' "' . $user['name']. '" has been deactivated');
				}
			}
			else
			{
				return Redirect::route('Cms.users.team.show', ['id' => $id])->withErrors($this->user_service->getError());
			}
		}
		else
		{
			// view
			$this->page->title 			= ucwords('Team');
			$this->page->subtitle 		= 'Delete ' . $user['name'] . ' (' . $user['role'] . ')';
			$this->page->active_menu 	= $this->active_menu;
			$this->page->breadcrumb 	= $this->breadcrumb;
			$this->page->breadcrumb[] 	= ['link' => route('Cms.users.team.show', ['_id' => $id]), 'label' => $user['username']];			
			return View::make('Cms::admin.pages.users.team.destroy')
						->with('data', $user)
						->with('page', $this->page)
						->with('me', $this->me)
						;
		}
	}

	function update_password()
	{

		//---------------------------------------------- GENERATE VIEW ----------------------------------------------
		$this->page->title 			= 'Update Password';
		$this->page->subtitle 		= '';
		$this->page->active_menu 	= $this->active_menu;
		$this->page->breadcrumb 	= $this->breadcrumb;

		// return view
		return View::make('Cms::admin.pages.users.team.update_password')
						->with('page', $this->page)
						->with('me', $this->me);
	}

	function store_update_password()
	{
		$rules['old_password'] = 'required';
		$rules['new_password'] = 'required|min:8|confirmed';

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->passes())
		{
			// check old password
			$user 			= $this->dispatch(new CheckUser(['email' => $this->me['email'], 'password' => Input::get('old_password')]));
			$check 			= json_decode($user);
			if (!$check->meta->success)
			{
				return Redirect::back()->withErrors(new MessageBag(['old_password' => 'Your old password is incorrect']));
			}
			else
			{
				$new_me 				=  json_decode(json_encode($check->data), true);
				$new_me['password'] 	= Input::get('new_password');
				unset($new_me['_id']);
				unset($new_me['created_at']);
				unset($new_me['updated_at']);

				$user 		= $this->dispatch(new SaveUser($new_me, $this->me['_id']));
				$check 			= json_decode($user);
				if ($check->meta->success!=true)
				{
					return Redirect::back()->withErrors(new MessageBag(['old_password' => $check->meta->errors]));
				}
				
				Session::flash('alert_success', 'Your password has been updated');
				return Redirect::back();
			}
		}
		else
		{
			return Redirect::back()->withErrors($validator);
		}

	}


}