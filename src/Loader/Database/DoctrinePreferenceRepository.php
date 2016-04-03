<?php

namespace Oxygen\Preferences\Loader\Database;

use Exception;
use Oxygen\Data\Exception\NoResultException;
use Oxygen\Data\Repository\Doctrine\Repository;

class DoctrinePreferenceRepository extends Repository implements PreferenceRepositoryInterface {

    /**
     * The name of the entity.
     *
     * @var string
     */

    protected $entityName = PreferenceItem::class;

    /**
     * Finds an preference item based upon the key.
     *
     * @param string $key
     * @return \Oxygen\Preferences\Loader\Database\PreferenceItem
     * @throws \Oxygen\Data\Exception\NoResultException if the key doesn't exist
     */
    public function findByKey($key) {
        $q = $this->getQuery(
            $this->createSelectQuery()
                 ->andWhere('o.key = :key')
                 ->setParameter('key', $key)
        );

        try {
            return $q->getSingleResult();
        } catch(Exception $e) {
            throw $this->makeNoResultException($e, $q);
        }
    }
}