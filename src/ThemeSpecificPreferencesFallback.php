<?php


namespace Oxygen\Preferences;


use Illuminate\Support\Arr;
use Oxygen\Core\Theme\Theme;
use Oxygen\Core\Theme\ThemeManager;
use Oxygen\Core\Theme\ThemeNotFoundException;

/**
 * Allow the use of theme-wide preferences as a fallback, when the required preference item is not set.
 *
 * @package Oxygen\Preferences
 */
class ThemeSpecificPreferencesFallback implements PreferencesStorageInterface {

    /**
     * @var ThemeManager
     */
    private $themeManager;

    /**
     * @var Theme
     */
    private $currentTheme = null;

    /**
     * @var string
     */
    private $key;

    public function __construct(ThemeManager $themeManager, string $key) {
        $this->themeManager = $themeManager;
        $this->key = $key;
    }

    /**
     * @return array
     */
    public function getPreferences(): array {
        $currentTheme = $this->getCurrentTheme();
        if(isset($currentTheme->getProvides()[$this->key])) {
            return $currentTheme->getProvides()[$this->key];
        } else {
            return [];
        }
    }

    /**
     * @throws \Exception
     */
    public function setPreferences(array $preferences) {
        throw new \Exception('ThemeSpecificPreferencesFallback is readonly');
    }

    private function getCurrentTheme(): Theme {
        if($this->currentTheme === null) {
            try {
                $this->currentTheme = $this->themeManager->current();
            } catch (ThemeNotFoundException $e) {
                // do nothing
            }
        }
        return $this->currentTheme;
    }
}
