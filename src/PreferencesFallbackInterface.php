<?php


namespace Oxygen\Preferences;


use Oxygen\Theme\Theme;
use Oxygen\Theme\ThemeNotFoundException;

interface PreferencesFallbackInterface {

    /**
     * Override the theme for the duration of the request, but do not store it for next time.
     *
     * @param string $override
     * @throws ThemeNotFoundException
     */
    public function temporarilyOverrideTheme(string $override);

    /**
     * @param string $key
     * @return null|mixed
     * @throws PreferenceNotFoundException
     */
    public function getPreferenceValue(string $key);

}
