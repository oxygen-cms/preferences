<?php

namespace Oxygen\Preferences;

use Oxygen\Core\Contracts\CoreConfiguration;

class PreferencesCoreConfiguration implements CoreConfiguration {

    /**
     * Constructs the preferences.
     *
     * @param \Oxygen\Preferences\PreferencesManager $preferences
     */
    public function __construct(PreferencesManager $preferences) {
        $this->preferences = $preferences;
    }

    /**
     * Returns the base URI to be used by Oxygen
     *
     * @return string
     */
    public function getAdminURIPrefix() {
        return $this->preferences->get('system.admin')->getRepository()->get('adminUriPrefix');
    }

    /**
     * Returns the base URI to be used by Oxygen
     *
     * @return string
     */
    public function getAdminLayout() {
        return $this->preferences->get('appearance.admin')->getRepository()->get('adminLayout');
    }
}