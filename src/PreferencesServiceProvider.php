<?php

namespace Oxygen\Preferences;

use Oxygen\Data\BaseServiceProvider;
use Oxygen\Data\Cache\CacheSettingsRepositoryInterface;
use Oxygen\Preferences\Cache\CacheSettingsRepository;
use Oxygen\Preferences\Loader\Database\DoctrinePreferenceRepository;
use Oxygen\Preferences\Loader\Database\PreferenceRepositoryInterface;
use Oxygen\Theme\ThemeLoader;
use Oxygen\Theme\ThemeManager;

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
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'oxygen/preferences');

        $this->loadMigrationsFrom(__DIR__ . '/../migrations');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register() {
        $this->loadEntitiesFrom(__DIR__ . '/Loader/Database');

        $this->app->bind(ThemeLoader::class, PreferencesThemeLoader::class); // from `oxygen/theme`
		$this->app->bind(CacheSettingsRepositoryInterface::class, CacheSettingsRepository::class); // from `oxygen/data`

        $this->app->bind(PreferenceRepositoryInterface::class, DoctrinePreferenceRepository::class);

        $this->app->singleton(ThemeSpecificPreferencesFallback::class, function() {
            return new ThemeSpecificPreferencesFallback($this->app[ThemeManager::class]);
        });
        $this->app->bind(PreferencesFallbackInterface::class, ThemeSpecificPreferencesFallback::class);
	    $this->app->singleton(PreferencesManager::class, function() {
	        return new PreferencesManager($this->app[PreferencesFallbackInterface::class]);
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
