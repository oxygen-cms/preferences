<?php

namespace Oxygen\Preferences\Loader;

use Oxygen\Data\Exception\InvalidEntityException;
use Oxygen\Preferences\ChainedStore;
use Oxygen\Data\Exception\NoResultException;
use Oxygen\Preferences\Loader\Database\PreferenceItem;
use Oxygen\Preferences\PreferenceNotFoundException;
use Oxygen\Preferences\PreferencesStorageInterface;
use Oxygen\Preferences\PreferencesStoreInterface;

class DatabaseLoader implements LoaderInterface {

    /**
     * Config repository to use.
     *
     * @var PreferenceRepositoryInterface
     */
    protected $repository;

    /**
     * Key
     *
     * @var string
     */
    protected $key;

    /**
     * @var ChainedStore
     */
    private $cachedRepository;

    /**
     * @var PreferenceItem
     */
    private $preferenceItem;

    /**
     * @var PreferencesStorageInterface|null
     */
    private $fallback;

    /**
     * Constructs the ConfigLoader.
     *
     * @param PreferenceRepositoryInterface $repository
     * @param string $key
     * @param PreferencesStorageInterface|null $fallback
     */
    public function __construct(PreferenceRepositoryInterface $repository, string $key, PreferencesStorageInterface $fallback = null) {
        $this->repository = $repository;
        $this->key = $key;
        $this->fallback = $fallback;
    }

    /**
     * Loads the preferences and returns the repository.
     *
     * @return PreferencesStoreInterface
     * @throws PreferenceNotFoundException
     */
    public function load(): PreferencesStoreInterface {
        if($this->cachedRepository === null) {
            try {
                $this->preferenceItem = $this->repository->findByKey($this->key);
                $chain = function() {
                        yield $this->preferenceItem->getPreferences();
                        if ($this->fallback !== null) {
                            yield $this->fallback->getPreferences();
                        }
                    };
                $this->cachedRepository = new ChainedStore($chain);
            } catch(NoResultException $e) {
                throw new PreferenceNotFoundException('Preference Key ' . $this->key . ' Not Found In Database', 0, $e);
            }
        }

        return $this->cachedRepository;
    }

    /**
     * Stores the preferences.
     *
     * @return void
     * @throws InvalidEntityException if the preferences were invalid
     */
    public function store() {
        if($this->cachedRepository !== null) {
            $this->preferenceItem->setPreferences(
                array_merge_recursive_distinct(
                    $this->preferenceItem->getPreferences(),
                    $this->cachedRepository->getChangedArray()
                )
            );
            $this->repository->persist($this->preferenceItem);
        }
    }

}
