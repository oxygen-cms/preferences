<?php

namespace Oxygen\Preferences\Cache;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\UnitOfWork;
use Oxygen\Data\Behaviour\PrimaryKeyInterface;
use Oxygen\Data\Cache\CacheSettingsRepositoryInterface;
use Oxygen\Preferences\Loader\Database\PreferenceRepositoryInterface;
use Oxygen\Preferences\PreferencesManager;

class CacheSettingsRepository implements CacheSettingsRepositoryInterface {

    protected $preferences;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $entityManager;

    protected $preferencesRepository;

    public function __construct(PreferencesManager $manager, EntityManager $entities, PreferenceRepositoryInterface $repository) {
        $this->preferences = $manager;
        $this->entityManager = $entities;
        $this->preferencesRepository = $repository;
    }

    /**
     * Returns a list of entities whose caches should be invalidated when any entity of the given class is updated.
     *
     * @param $className
     * @return array
     */
    public function get($className) {
        $entities = $this->preferences->getSchema('cacheSettings')->getRepository()->get('entities', []);
        return array_get($entities, $className, []);
    }

    /**
     * This relies of the fact that the preferences use the `DatabaseLoader` most of the time.
     *
     * @param bool|false $withinOnFlush
     */
    public function persist($withinOnFlush = false) {
        // special handling for 'persisting' inside Doctrine's `onFlush` event
        if($withinOnFlush) {
            $entity = $this->preferencesRepository->findByKey('cacheSettings');
            // manually changes the preferences
            $entity->setPreferences($this->preferences->getSchema('cacheSettings')->getRepository());
            $metadata = $this->entityManager->getClassMetadata(get_class($entity));
            $this->entityManager->getUnitOfWork()->computeChangeSet($metadata, $entity);
        } else {
            $this->preferences->getSchema('cacheSettings')->storeRepository();
        }
    }

    public function add($class, PrimaryKeyInterface $entity) {
        $deps = array_get($this->preferences->get('cacheSettings::entities'), $class, []);
        $deps[] = $this->getInfo($entity);

        // removes duplicates from the array
        $deps = array_intersect_key($deps, array_unique(array_map('serialize', $deps)));

        $this->preferences->getSchema('cacheSettings')->getRepository()->set('entities.' .$class, $deps);
    }

    public function remove($class, PrimaryKeyInterface $entity) {
        $deps = array_get($this->preferences->get('cacheSettings::entities'), $class, []);
        $info = $this->getInfo($entity);
        $deps = array_filter($deps, function($value) use($info) {
            return $value != $info;
        });
        $this->preferences->getSchema('cacheSettings')->getRepository()->set('entities.' . $class, $deps);
    }

    private function getInfo(PrimaryKeyInterface $object) {
        return ['id' => $object->getId(), 'class' => get_class($object)];
    }
}