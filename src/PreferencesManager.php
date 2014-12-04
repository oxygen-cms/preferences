<?php

namespace Oxygen\Preferences;

use DirectoryIterator;
use InvalidArgumentException;

class PreferencesManager {

    /**
     * Schemas of the PreferencesManager
     *
     * @var array
     */

    protected $schemas;

    /**
     * Lazy-loaded schemas of the PreferencesManager
     *
     * @var array
     */

    protected $lazySchemas;

    /**
     * Groups of the PreferencesManager
     *
     * @var array
     */

    protected $groups;

    /**
     * Constructs the PreferencesManager.
     */

    public function __construct() {
        $this->schemas = [];
        $this->lazySchemas = [];
        $this->groups  = [];
    }

    /**
     * Loads a directory of files.
     *
     * @param string $directory
     * @param array $order
     * @return void
     */

    public function loadDirectory($directory, array $order = []) {
        if(!empty($order)) {
            $this->loadOrderedDirectory($directory, $order);
        } else {
            $iterator = new DirectoryIterator($directory);
            foreach($iterator as $file) {
                if($file->isFile() && ends_with($file->getFilename(), '.php')) {
                    require $directory . '/' . $file->getFilename();
                }
            }
        }
    }

    /**
     * Loads a directory of files, in the order specified by $order.
     *
     * @param string $directory
     * @param array $order
     * @return void
     */

    protected function loadOrderedDirectory($directory, array $order) {
        foreach($order as $item) {
            require $directory . '/' . $item . '.php';
        }
    }

    /**
     * Register a config schema with the PreferencesManager
     *
     * @param string $key
     * @param callable $callback
     * @return void
     */

    public function register($key, callable $callback) {
        $this->addLazy($key, $callback);
    }

    /**
     * Adds a lazy loaded preferences schema.
     *
     * @param string $key
     * @param callable $callback
     */

    protected function addLazy($key, callable $callback) {
        $this->lazySchemas[$key] = $callback;
    }

    /**
     * Edits a schema from the PreferencesManager
     *
     * @param string $key
     * @param callable $callback
     * @return Schema
     */

    public function edit($key, callable $callback) {
        $schema = $this->get($key);
        $callback($schema);
        $this->add($schema);
    }

    /**
     * Retrieves a schema or group from the PreferencesManager
     *
     * @param string $key
     * @return Schema
     * @throws InvalidArgumentException If the key can't be found.
     */

    public function get($key) {
        $this->load($key);

        $schema = array_get($this->schemas, $key, null);
        if($schema === null) {
            throw new InvalidArgumentException('Key "' . $key . '" not found.');
        }
        return $schema;
    }

    /**
     * Loads the callback of the given schema.
     *
     * @param string $key
     * @return boolean
     */

    public function load($key) {
        if($this->isRoot($key)) {
            $schemas = $this->lazySchemas;
        } else {
            $schemas = array_where($this->lazySchemas, function($possibleKey) use($key) {
                return starts_with($possibleKey, $key);
            });
        }

        if(empty($schemas)) {
            return false;
        }

        foreach($schemas as $key => $callback) {
            unset($this->lazySchemas[$key]);
            $schema = new Schema($key);
            $callback($schema);
            $this->add($schema);
        }

        return true;
    }

    /**
     * Adds a schema to the PreferencesManager
     *
     * @param Schema $schema
     * @return Schema
     */

    public function add(Schema $schema) {
        array_set($this->schemas, $schema->getKey(), $schema);
    }

    /**
     * Determines if PreferencesManager has the given key.
     *
     * @param string $key
     * @return boolean
     */

    public function has($key) {
        $this->load($key);

        return array_get($this->schemas, $key, null) !== null;
    }

    /**
     * Returns the raw $schemas array
     *
     * @return array
     */

    public function all() {
        $this->load('');

        return $this->schemas;
    }

    /**
     * Register a group with the PreferencesManager
     *
     * @param string $key
     * @param string $name
     * @return void
     */

    public function addGroup($key, $name) {
        $this->groups[$key] = $name;
    }

    /**
     * Gets the group's name.
     *
     * @param string $key
     * @return string
     */

    public function group($key) {
        return isset($this->groups[$key]) ? $this->groups[$key] : $key;
    }

    /**
     * Determines if the given key is 'root'
     *
     * @param string $key
     * @return boolean
     */

    public function isRoot($key) {
        return $key === '' || $key === null;
    }

    /**
     * Determines the parent group for the given key.
     *
     * @param string $key
     * @return string
     */

    public function parentGroup($key) {
        $parts = explode('.', $key);
        array_pop($parts);
        return implode('.', $parts);
    }

}