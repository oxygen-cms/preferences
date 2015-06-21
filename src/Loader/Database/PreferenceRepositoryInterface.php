<?php

namespace Oxygen\Preferences\Loader\Database;

use Oxygen\Data\Repository\RepositoryInterface;

interface PreferenceRepositoryInterface extends RepositoryInterface {

    /**
     * Finds an preference item based upon the key.
     *
     * @param string $key
     * @return PreferenceItem
     */
    public function findByKey($key);

}