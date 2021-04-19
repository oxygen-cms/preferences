<?php


namespace Oxygen\Preferences;


use Illuminate\Support\Arr;
use Oxygen\Theme\Theme;
use Oxygen\Theme\ThemeManager;
use Oxygen\Theme\ThemeNotFoundException;

/**
 * Allow the use of theme-wide preferences as a fallback, when the required preference item is not set.
 *
 * @package Oxygen\Preferences
 */
class ThemeSpecificPreferencesFallback implements PreferencesFallbackInterface {

    /**
     * @var ThemeManager
     */
    private $themeManager;
    /**
     * @var Theme
     */
    private $currentTheme = null;

    public function __construct(ThemeManager $themeManager) {
        $this->themeManager = $themeManager;
    }

    /**
     * Override the theme for the duration of the request, but do not store it for next time.
     *
     * @param string $override
     * @throws ThemeNotFoundException
     */
    public function temporarilyOverrideTheme(string $override) {
        $this->currentTheme = $this->themeManager->get($override);
    }

    /**
     * @param string $key
     * @return null|mixed
     * @throws PreferenceNotFoundException
     */
    public function getPreferenceValue(string $key) {
        $currentTheme = $this->getCurrentTheme();
        $keyParts = explode('::', $key);
        if ($currentTheme !== null && isset($currentTheme->getProvides()[$keyParts[0]])) {
            if(isset($currentTheme->getProvides()[$keyParts[0]][$keyParts[1]])) {
                return $currentTheme->getProvides()[$keyParts[0]][$keyParts[1]];
            }
        }
        throw new PreferenceNotFoundException('No value for key `' . $key . '` in theme ' . ($currentTheme === null ? '[no theme]' : '"' . $currentTheme->getName() . '"'));
    }

    private function getCurrentTheme() {
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
