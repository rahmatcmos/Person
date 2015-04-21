<?php namespace ThunderID\Person\Controllers;

use \App\Http\Controllers\Controller;
use \ThunderID\Person\Models\Person;
use \ThunderID\Organisation\Models\Organisation;
use \ThunderID\Commoquent\Checking;
use \ThunderID\Commoquent\Getting;
use \Input;

class AuthController extends Controller {

	public function __construct()
	{
		//
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	function user()
	{
		$content 								= $this->dispatch(new Checking(new Person, ['username' => Input::get('username'), 'password' => Input::get('password')]));

		return $content;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	function api()
	{
		$content 								= $this->dispatch(new Checking(new Authentication, ['client' => Input::get('client'), 'secret' => Input::get('secret')]));

		return $content;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	function person()
	{
		$content 								= $this->dispatch(new Getting(new Person, ['id' => Input::get('id'), 'CurrentWork' => '', 'checkwidget' => 'order'], ['created_at' => 'asc'],1, 1));

		return $content;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	function organisation()
	{
		$content 								= $this->dispatch(new Getting(new Organisation, ['id' => Input::get('id')], 1, 1));

		return $content;
	}
}