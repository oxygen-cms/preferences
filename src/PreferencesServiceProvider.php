<?php

namespace Oxygen\Preferences;

use Oxygen\Core\Contracts\CoreConfiguration;
use Oxygen\Data\BaseServiceProvider;
use Oxygen\Preferences\Loader\Database\DoctrinePreferenceRepository;
use Oxygen\Preferences\Loader\Database\PreferenceRepositoryInterface;
use Oxygen\Theme\ThemeLoader;

class PreferencesServiceProvider extends BaseServiceProvider {

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
        $this->app[PreferencesManager::class]->loadDirectory(__DIR__ . '/../resources/preferences');
        $this->loadEntitiesFrom(__DIR__ . '/Loader/Database');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'oxygen/preferences');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */

	public function register() {
        $this->app->bind(ThemeLoader::class, PreferencesThemeLoader::class);
        $this->app->bind(CoreConfiguration::class, PreferencesCoreConfiguration::class);

        $this->app->bind(PreferenceRepositoryInterface::class, DoctrinePreferenceRepository::class);

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
