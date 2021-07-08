<?php


namespace Oxygen\Preferences;

interface PreferencesStorageInterface {

    /**
     * Returns an array of preferences.
     *
     * @return array
     */
    public function getPreferences();

}
