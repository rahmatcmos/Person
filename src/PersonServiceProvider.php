<?php namespace ThunderID\Person;

use View, Validator, App, Route, Auth, Request, Redirect;
use Illuminate\Support\ServiceProvider;

class PersonServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		\ThunderID\Person\Models\Person::observe(new \ThunderID\Person\Models\Observers\PersonObserver);
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		\ThunderID\Person\Models\Person::observe(new \ThunderID\Person\Models\Observers\PersonObserver);
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}
