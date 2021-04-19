<?php

namespace Oxygen\Preferences;

use Oxygen\Preferences\Loader\LoaderInterface;
use Illuminate\Support\Arr;

class Repository {

    /**
     * Array of preferences
     *
     * @var array
     */
    protected $preferences;

    /**
     * Preferences that have changed.
     *
     * @var array
     */
    protected $changed;

    /**
     * @var LoaderInterface
     */
    private $loader;

    /**
     * Constructs the Preferences Repository
     *
     * @param array $preferences
     */
    public function __construct(array $preferences) {
        $this->preferences = $preferences;
        $this->changed = [];
    }

    /**
     * Adds fallback preferences to use if a key doesn't exist in this repository.
     *
     * @param Repository $repository
     */
    public function addFallbackRepository($repository) {
        $this->preferences = $this->toArray() + $repository->toArray();
    }

    /**
     * Get the specified preferences item.
     *
     * @param string $key key using dot notation
     * @param mixed $default default value
     * @return mixed
     */
    public function get($key, $default = null) {
        return Arr::get($this->preferences, $key, $default);
    }

    /**
     * Sets the specified preferences item.
     * Will not persist changes automatically.
     *
     * @param string $key key using dot notation
     * @param mixed $value new value
     * @return void
     */
    public function set($key, $value) {
        $old = Arr::get($this->preferences, $key, null);
        if($old !== $value) {
            Arr::set($this->preferences, $key, $value);
            $this->changed[$key] = true;
        }
    }

    /**
     * Checks if the preferences item has been set.
     *
     * @param string $key key using dot notation
     * @return boolean
     */
    public function has($key) {
        return Arr::get($this->preferences, $key, null) != null;
    }

    /**
     * Determines if the specified preferences item has changed since last being persisted.
     * In other words, if the key needs to be persisted again.
     *
     * @param string $key key using dot notation
     * @return boolean
     */
    public function hasChanged($key) {
        return isset($this->changed[$key]) ? $this->changed[$key] : false;
    }

    /**
     * Returns the raw preferences array.
     *
     * @return array
     */
    public function toArray() {
        return $this->preferences;
    }

    /**
     * Set the loader.
     *
     * @param LoaderInterface $loader
     * @return void
     */
    public function setLoader(LoaderInterface $loader) {
        $this->loader = $loader;
    }

    /**
     * Fills the repository with an array of input data.
     *
     * @param array $input
     * @return void
     */
    public function fill(array $input) {
        foreach($input as $key => $value) {
            $this->set($key, $value);
        }
    }

}
