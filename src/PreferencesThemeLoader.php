<?php


namespace Oxygen\Preferences;

use Oxygen\Theme\ThemeLoader;

class PreferencesThemeLoader implements ThemeLoader {

    protected $preferences;

    public function __construct(PreferencesManager $preferences) {
        $this->preferences = $preferences;
    }

    /**
     * Returns the current theme.
     *
     * @return string
     */
    public function getCurrentTheme() {
        return $this->preferences->getSchema('appearance.themes::theme');
    }

    /**
     * Changes the current theme.
     *
     * @param string $theme the name of the new theme
     */
    public function setCurrentTheme($theme) {
        $schema = $this->preferences->getSchema('appearance.themes');
        $schema->getRepository()->set('theme', $theme);
        $schema->storeRepository();
    }
}