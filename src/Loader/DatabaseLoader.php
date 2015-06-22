<?php

namespace Oxygen\Preferences\Loader;

use Oxygen\Preferences\Loader\Database\PreferenceRepositoryInterface;
use Oxygen\Preferences\Repository;
use Oxygen\Preferences\Schema;

class DatabaseLoader implements LoaderInterface {

    /**
     * Config repository to use.
     *
     * @var Repository
     */
    protected $repository;

    /**
     * Key
     *
     * @var string
     */
    protected $key;

    /**
     * Constructs the ConfigLoader.
     *
     * @param PreferenceRepositoryInterface $repository
     */
    public function __construct(PreferenceRepositoryInterface $repository, $key) {
        $this->repository = $repository;
        $this->key = $key;
    }

    /**
     * Loads the preferences and returns the repository.
     *
     * @return Repository
     */
    public function load() {
        return $this->repository->findByKey($this->key)->getPreferences();
    }

    /**
     * Stores the preferences.
     *
     * @param Repository $repository
     * @return void
     */
    public function store(Repository $repository) {
        $item = $this->repository->findByKey($this->key);
        $item->setPreferences($repository);
        $this->repository->persist($item);
    }

}