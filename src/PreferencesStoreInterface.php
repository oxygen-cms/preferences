<?php


namespace Oxygen\Preferences;


interface PreferencesStoreInterface {

    /**
     * Get the specified preferences item. Throws if not found.
     *
     * @param string $key key using dot notation
     * @return mixed
     */
    public function get(string $key);

    /**
     * Get the specified preferences item.
     *
     * @param string $key
     * @param $default
     * @return mixed
     */
    public function getOrDefault(string $key, $default);

    /**
     * Sets the specified preferences item.
     * Will not persist changes automatically.
     *
     * @param string $key key using dot notation
     * @param mixed $value new value
     * @return void
     */
    public function set(string $key, $value);

    /**
     * Checks if the preferences item has been set.
     *
     * @param string $key key using dot notation
     * @return boolean
     */
    public function has(string $key): bool;
}
