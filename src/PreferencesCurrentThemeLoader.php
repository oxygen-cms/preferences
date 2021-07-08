<?php


namespace Oxygen\Preferences;

use Illuminate\Contracts\Container\Container;
use Oxygen\Theme\CurrentThemeLoader;
use Oxygen\Theme\ThemeNotFoundException;

class PreferencesCurrentThemeLoader implements CurrentThemeLoader {

    /**
     * @var Container
     */
    private $container;

    public function __construct(Container $container) {
        $this->container = $container;
    }

    /**
     * Returns the current theme.
     *
     * @return string
     * @throws ThemeNotFoundException
     */
    public function getCurrentTheme(): string {
        try {
            return $this->container->get(PreferencesManager::class)->getSchema('appearance.themes')->getRepository()->get('theme');
        } catch(PreferenceNotFoundException $e) {
            throw new ThemeNotFoundException($e);
        }
    }

    /**
     * Changes the current theme.
     *
     * @param string $theme the name of the new theme
     */
    public function setCurrentTheme(string $theme) {
        $schema = $this->container->get(PreferencesManager::class)->getSchema('appearance.themes');
        $schema->getRepository()->set('theme', $theme);
        $schema->storeRepository();
    }
}
