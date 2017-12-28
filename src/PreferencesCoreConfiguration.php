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
        try {
            return $this->preferences->get('system.admin::adminUriPrefix');
        } catch(\Exception $e) {
            return 'oxygen';
        }
    }

    /**
     * Returns the layout for the administration section.
     *
     * @return string
     */
    public function getAdminLayout() {
        return $this->preferences->get('appearance.admin::adminLayout', 'oxygen/ui-base::layout.main');
    }
}
