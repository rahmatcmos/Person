<?php namespace ThunderID\Person;

use Illuminate\Support\ServiceProvider;

class PersonServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->publishes([
		    realpath(__DIR__.'../../migrations') => $this->app->databasePath().'/migrations',
		]);
	}

	public function register()
	{
		//
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return [];
	}

}
