<?php

namespace Oxygen\Preferences\Loader;

use Oxygen\Data\Repository\RepositoryInterface;
use Oxygen\Preferences\PreferencesStorageInterface;

interface PreferenceRepositoryInterface extends RepositoryInterface {

    /**
     * Finds an preference item based upon the key.
     *
     * @param string $key
     * @return PreferencesStorageInterface
     */
    public function findByKey($key);

}
