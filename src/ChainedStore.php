<?php


namespace Oxygen\Preferences;

use Closure;
use Generator;
use Illuminate\Support\Arr;

class ChainedStore implements PreferencesStoreInterface {

    /**
     * A function which returns an iterator over preferences.
     * Preferences will be searched
     *
     * @var Closure
     */
    protected $preferencesGenerator;

    /**
     * Preferences that have changed.
     *
     * @var array
     */
    protected $changed;

    /**
     * @var array
     */
    private $preferencesOverlay;

    /**
     * Constructs a Chained preferences store
     *
     * @param Closure $preferencesGenerator
     */
    public function __construct(Closure $preferencesGenerator) {
        $this->preferencesGenerator = $preferencesGenerator;
        $this->preferencesOverlay = [];
        $this->changed = [];
    }

    /**
     * Get the specified preferences item.
     *
     * @param string $key key using dot notation
     * @return mixed
     * @throws PreferenceNotFoundException
     */
    public function get(string $key) {
        $cached = Arr::get($this->preferencesOverlay, $key);
        if($cached !== null) {
            return $cached;
        }
        $generator = $this->getGenerator();
        foreach($generator as $preferences) {
            $value = Arr::get($preferences, $key);
            if($value !== null) {
                return $value;
            }
        }
        throw new PreferenceNotFoundException('Preference not found with key ' . $key);
    }

    /**
     * Sets the specified preferences item.
     * Will not persist changes automatically.
     *
     * @param string $key key using dot notation
     * @param mixed $value new value
     * @return void
     */
    public function set(string $key, $value) {
        $old = $this->getOrDefault($key, null);
        if($old !== $value) {
            Arr::set($this->preferencesOverlay, $key, $value);
        }
    }

    /**
     * Checks if the preferences item has been set.
     *
     * @param string $key key using dot notation
     * @return boolean
     */
    public function has(string $key): bool {
        try {
            $this->get($key);
            return true;
        } catch(PreferenceNotFoundException $e) {
            return false;
        }
    }

    /**
     * Determines if the specified preferences item has changed since last being persisted.
     * In other words, if the key needs to be persisted again.
     *
     * @param string $key key using dot notation
     * @return boolean
     */
    public function hasChanged(string $key): bool {
        return Arr::has($this->preferencesOverlay, $key);
    }

    /**
     * Get the specified preferences item.
     *
     * @param string $key
     * @param $default
     * @return mixed
     */
    public function getOrDefault(string $key, $default) {
        try {
            return $this->get($key);
        } catch(PreferenceNotFoundException $e) {
            return $default;
        }
    }

    public function getChangedArray(): array {
        return $this->preferencesOverlay;
    }

    public function getGenerator(): Generator {
        return ($this->preferencesGenerator)();
    }
}
