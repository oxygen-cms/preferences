<?php

namespace Oxygen\Preferences\Loader\Database;

use Doctrine\ORM\Mapping AS ORM;
use Oxygen\Data\Behaviour\PrimaryKey;
use Oxygen\Data\Behaviour\Accessors;
use Oxygen\Data\Validation\Validatable;
use Oxygen\Preferences\Repository;
use Oxygen\Preferences\Transformer\JsonTransformer;

/**
 * @ORM\Entity
 * @ORM\Table(name="preferences")
 * @ORM\HasLifecycleCallbacks
 */
class PreferenceItem implements Validatable {

    use PrimaryKey;

    /**
     * @ORM\Column(name="``key`", type="string")
     */
    protected $key;

    /**
     * @ORM\Column(type="text")
     */
    protected $contents;

    /**
     * JsonTransformer loads preferences from a JSON string.
     *
     * @var JsonTransformer
     */
    protected static $jsonTransformer;

    /**
     *
     * Preferences repository encapsulates the storage and retrieval of preferences.
     *
     * @var Repository
     */
    protected $preferencesRepository;

    /**
     * Returns the key of the preferences.
     * @return string
     */
    public function getKey() {
        return $this->key;
    }

    /**
     * Sets the key of the preferences.
     * @param string $key
     */
    public function setKey($key) {
        $this->key = $key;
    }

    /**
     * Returns the preferences repository.
     * Creates a new repository if one doesn't already exist.
     *
     * @return Repository
     */
    public function getPreferences() {
        if($this->preferencesRepository === null) {
            $this->preferencesRepository = $this->createPreferencesRepository();
        }

        return $this->preferencesRepository;
    }

    /**
     * Sets the preferences repository.
     *
     * @param Repository $repository
     * @return void
     */
    public function setPreferences(Repository $repository) {
        $this->preferencesRepository = $repository;
        $this->syncPreferences();
    }

    /**
     * Sets the raw preferences field.
     *
     * @param string $preferences
     * @return $this
     */
    public function setPreferencesAsString($preferences) {
        $this->preferences = $preferences;
        $this->preferencesRepository = $this->createPreferencesRepository();
        return $this;
    }

    /**
     * Returns a new preferences repository from the given preferences.
     *
     * @return Repository
     */
    public function createPreferencesRepository() {
        $this->createJsonTransformer();
        return static::$jsonTransformer->toRepository($this->contents);
    }

    /**
     * Sync the preferences repository back with the model's `preferences` attribute.
     *
     * @return void
     */
    public function syncPreferences() {
        $this->createJsonTransformer();
        $this->contents = static::$jsonTransformer->fromRepository($this->getPreferences(), true);
    }

    /**
     * Creates the json transformer if needed.
     *
     * @return void
     */
    protected function createJsonTransformer() {
        if(static::$jsonTransformer === null) {
            static::$jsonTransformer = new JsonTransformer();
        }
    }

    /**
     * Returns an array of validation rules used to validate the model.
     *
     * @return array
     */
    public function getValidationRules() {
        return [
            'key' => [
                'required',
                'alphaDot'
            ]
        ];
    }

}