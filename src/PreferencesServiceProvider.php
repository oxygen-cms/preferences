<?php

namespace OxygenModule\Preferences;

use Illuminate\Support\ServiceProvider;

use Oxygen\Core\Html\Navigation\Navigation;
use Oxygen\Preferences\Loader\ConfigLoader;

class PreferencesServiceProvider extends ServiceProvider {

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

	public function boot() {
        $this->app['oxygen.preferences']->loadDirectory(__DIR__ . '/../resources/preferences');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */

	public function register() {
	    $this->app->singleton(PreferencesManager::class, function() {
	        return new PreferencesManager();
	    });
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */

	public function provides() {
		return [
            PreferencesManager::class
		];
	}

}
