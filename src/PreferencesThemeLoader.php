<?php


namespace Oxygen\Preferences;

use Oxygen\Theme\ThemeLoader;
use Oxygen\Theme\ThemeNotFoundException;

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
        try {
            return $this->preferences->get('appearance.themes::theme');
        } catch(PreferenceNotFoundException $e) {
            throw new ThemeNotFoundException($e);
        }
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