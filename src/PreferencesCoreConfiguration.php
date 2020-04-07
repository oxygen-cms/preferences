<?php

namespace Oxygen\Preferences;

use Exception;
use Oxygen\Core\Contracts\CoreConfiguration;

class PreferencesCoreConfiguration implements CoreConfiguration {
    /**
     * @var PreferencesManager
     */
    private $preferences;

    /**
     * Constructs the preferences.
     *
     * @param PreferencesManager $preferences
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
        } catch(Exception $e) {
            return 'oxygen';
        }
    }

    /**
     * Returns the layout for the administration section.
     *
     * @return string
     * @throws PreferenceNotFoundException
     */
    public function getAdminLayout() {
        return $this->preferences->get('appearance.admin::adminLayout', 'oxygen/ui-base::layout.main');
    }
}
