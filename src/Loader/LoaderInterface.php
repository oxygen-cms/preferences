<?php

namespace Oxygen\Preferences\Loader;

use Oxygen\Preferences\Repository;
use Oxygen\Preferences\Schema;

interface LoaderInterface {

    /**
     * Loads the preferences and returns the repository.
     *
     * @return Repository
     */

    public function load();

    /**
     * Stores the preferences.
     *
     * @param Repository $repository
     * @return void
     */

    public function store(Repository $repository, Schema $schema);

}