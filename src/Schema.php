<?php

namespace Oxygen\Preferences;

use Oxygen\Core\Form\FieldMetadata;
use Oxygen\Preferences\Loader\LoaderInterface;

class Schema {

    /**
     * The key of the preferences schema.
     *
     * @var string
     */

    protected $key;

    /**
     * The title of the preferences schema.
     *
     * @var string
     */

    protected $title;

    /**
     * The fields the user can edit.
     *
     * @var array
     */

    protected $fields;

    /**
     * The LoaderInterface.
     *
     * @var LoaderInterface
     */

    protected $loader;

    /**
     * The Repository.
     *
     * @var Repository
     */

    protected $repository;

    /**
     * A custom view file to be displayed.
     *
     * @var string
     */

    protected $view;

    /**
     * Constructs the Schema
     *
     * @param string $key
     */

    public function __construct($key) {
        $this->key      = $key;
        $this->title    = $key;
        $this->fields   = [];
    }

    /**
     * Get the key of the preferences schema.
     *
     * @return string
     */

    public function getKey() {
        return $this->key;
    }

    /**
     * Get the title.
     *
     * @return string
     */

    public function getTitle() {
        return $this->title;
    }

    /**
     * Set the title of the preferences schema.
     *
     * @param string $title
     * @return void
     */

    public function setTitle($title) {
        $this->title = $title;
    }

    /**
     * Loads the Preferences repository.
     *
     * @param LoaderInterface|callable $loader
     * @return void
     */

    public function setLoader($loader) {
        $this->loader = $loader;
    }

    /**
     * Resolves the loader.
     *
     * @return void
     */

    protected function resolveLoader() {
        if(is_callable($this->loader)) {
            $callable = $this->loader;
            $this->loader = $callable();
        }
    }

    /**
     * Loads the Preferences repository.
     *
     * @return void
     */

    public function loadRepository() {
        $this->resolveLoader();

        $this->repository = $this->loader->load();
    }

    /**
     * Stores the Preferences repository.
     *
     * @return void
     */

    public function storeRepository() {
        $this->resolveLoader();

        $this->loader->store($this->repository, $this);
    }

    /**
     * Returns the Preferences repository.
     *
     * @return Repository
     */

    public function getRepository() {
        if($this->repository === null) {
            $this->loadRepository();
        }

        return $this->repository;
    }

    /**
     * Add a field.
     *
     * @param FieldMetadata $field
     * @return void
     */

    public function addField(FieldMetadata $field, $group = '', $subgroup = '') {
        if(!isset($this->fields[$group])) {
            $this->fields[$group] = [];
        }
        if(!isset($this->fields[$group][$subgroup])) {
            $this->fields[$group][$subgroup] = [];
        }
        $this->fields[$group][$subgroup][$field->name] = $field;
    }

    /**
     * Add a field.
     *
     * @param array $parameters
     * @param string $group
     * @param string $subgroup
     * @return void
     */

    public function makeField(array $parameters, $group = '', $subgroup = '') {
        $field = new FieldMetadata($parameters['name'], FieldMetadata::TYPE_TEXT, true);
        unset($parameters['name']);
        foreach($parameters as $key => $value) {
            $field->$key = $value;
        }
        $this->addField($field, $group, $subgroup);
    }

    /**
     * Adds multiple fields.
     *
     * @param array $fields
     * @return void
     */

    public function makeFields(array $fields) {
        foreach($fields as $groupName => $groupItems) {
            foreach($groupItems as $subgroupName => $subgroupItems) {
                if(!is_array($subgroupItems)) {
                    break;
                }
                foreach($subgroupItems as $item) {
                    $this->makeField($item, $groupName, $subgroupName);
                }
            }
        }
    }

    /**
     * Returns a field by its name.
     *
     * @param string $name
     * @return FieldMetadata
     */

    public function getField($name, $group = '', $subgroup = '') {
        return $this->fields[$group][$subgroup][$name];
    }

    /**
     * Returns all fields.
     *
     * @return array
     */

    public function getFields() {
        return $this->fields;
    }

    /**
     * Sets the view.
     *
     * @param string $view
     * @return void
     */

    public function setView($view) {
        $this->view = $view;
    }

    /**
     * Determines if the view has been set.
     *
     * @return boolean
     */

    public function hasView() {
        return $this->view !== null;
    }

    /**
     * Returns the view.
     *
     * @return string
     */

    public function getView() {
        return $this->view;
    }

    /**
     * Returns the combined validation rules for all the fields.
     *
     * @return array
     */

    public function getValidationRules() {
        $rules = [];

        foreach(array_dot($this->fields) as $field) {
            $rules[$field->name] = $field->validationRules;
            if(is_string($rules[$field->name])) {
                $rules[$field->name] = implode('|', $rules);
            }
        }

        return $rules;
    }

}