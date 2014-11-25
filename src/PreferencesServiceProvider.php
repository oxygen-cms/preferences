<?php

namespace Oxygen\Preferences;

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
		$this->package('oxygen/preferences', 'oxygen/preferences', __DIR__ . '/../resources');

        $this->app['oxygen.blueprintManager']->loadDirectory(__DIR__ . '/../resources/blueprints');
        $this->app['oxygen.preferences']->loadDirectory(__DIR__ . '/../resources/preferences');

        $this->addNavigationItems();
	}

	/**
	 * Adds items the the admin navigation.
	 *
	 * @return void
	 */

	public function addNavigationItems() {
		$blueprint = $this->app['oxygen.blueprintManager']->get('Preferences');
		$nav = $this->app['oxygen.navigation'];

		$nav->add($blueprint->getToolbarItem('getView'));
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */

	public function register() {
	    $this->app->bindShared(['oxygen.preferences' => 'Oxygen\Preferences\PreferencesManager'], function() {
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
			'oxygen.preferences'
		];
	}

}
