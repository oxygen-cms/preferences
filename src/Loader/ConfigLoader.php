<?php

namespace Oxygen\Preferences\Loader;

use Illuminate\Config\Repository as Config;
use Oxygen\Preferences\Repository;
use Oxygen\Preferences\Schema;

class ConfigLoader implements LoaderInterface {

    /**
     * Config repository to use.
     *
     * @var Repository
     */

    protected $config;

    /**
     * Key
     *
     * @var string
     */

    protected $key;

    /**
     * Constructs the ConfigLoader.
     *
     * @param Repository $config
     */
    public function __construct(Config $config, $key) {
        $this->config = $config;
        $this->key = $key;
    }

    /**
     * Loads the preferences and returns the repository.
     *
     * @return Repository
     */
    public function load() {
        return new Repository($this->config->get($this->key));
    }

    /**
     * Stores the preferences.
     *
     * @param Repository $repository
     * @param Schema $schema
     * @return void
     */
    public function store(Repository $repository, Schema $schema) {
        foreach(array_flatten($schema->getFields()) as $field) {
            if($repository->hasChanged($field->name)) {
                $this->config->write($this->key . '.' . $field->name, $repository->get($field->name));
            } else {
            }
        }
    }

}