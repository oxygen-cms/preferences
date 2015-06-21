<?php

namespace Oxygen\Preferences\Loader\Database;

use Exception;
use Oxygen\Data\Exception\NoResultException;
use Oxygen\Data\Repository\Doctrine\Repository;

class DoctrinePreferenceRepository extends Repository implements PreferenceRepositoryInterface {

    /**
     * Finds an preference item based upon the key.
     *
     * @param string $key
     * @return \Oxygen\Preferences\Loader\Database\PreferenceItem
     * @throws \Oxygen\Data\Exception\NoResultException if the key doesn't exist
     */
    public function findByKey($key) {
        $qb = $this->getQuery(
            $this->createSelectQuery()
                 ->andWhere('o.key = :key')
                 ->setParameter('key', $key)
        );

        try {
            return $qb->getSingleResult();
        } catch(Exception $e) {
            throw new NoResultException($e, $this->replaceQueryParameters($qb->getDQL(), $qb->getParameters()));
        }
    }
}