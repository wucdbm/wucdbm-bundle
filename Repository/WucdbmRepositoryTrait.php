<?php

namespace Wucdbm\Bundle\WucdbmBundle\Repository;

use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Wucdbm\Bundle\WucdbmBundle\Filter\AbstractFilter;

trait WucdbmRepositoryTrait {

    /**
     * @param string $alias
     * @param null $indexBy
     * @return QueryBuilder
     */
    public function createQueryBuilder($alias, $indexBy = null) {
        return $this->getEntityManager()->createQueryBuilder()
            ->select($alias)
            ->from($this->getEntityName(), $alias, $indexBy);
    }

    /**
     * @param QueryBuilder $builder
     * @param AbstractFilter $filter
     * @param $groupBy
     *
     * @return array|Paginator
     */
    public function returnFilteredEntities(QueryBuilder $builder, AbstractFilter $filter, $groupBy = null) {
        $pagination = $filter->getPagination();

        if ($pagination->getLimit()) {
            $builder->setMaxResults($pagination->getLimit());
        }
        $builder->setFirstResult($pagination->getOffset());

        if ($pagination->isEnabled()) {
            $query = $builder->getQuery();
            $query->setHydrationMode($filter->getHydrationMode());
            $paginator = new Paginator($query, true);
            $pagination->setTotalResults(count($paginator));
            return $paginator;
        }

        if ($filter->isHydrationArray() && $groupBy) {
            $builder->groupBy($groupBy);
        }

        $query    = $builder->getQuery();
        $entities = $query->getResult($filter->getHydrationMode());
        return $entities;
    }

    /**
     * @param QueryBuilder $builder
     * @param AbstractFilter $filter
     *
     * @return mixed
     */
    public function returnFilteredEntity(QueryBuilder $builder, AbstractFilter $filter) {
        $query = $builder->getQuery();
        $query->setHydrationMode($filter->getHydrationMode());
        try {
            $entity = $query->getSingleResult();
        } catch (NoResultException $e) {
            return null;
        }
        return $entity;
    }

    /**
     * @param QueryBuilder $builder
     * @param AbstractFilter $filter
     *
     * @return mixed
     */
    public function returnFirstFilteredEntity(QueryBuilder $builder, AbstractFilter $filter) {
        $builder->setMaxResults(1);
        return $builder->getQuery()->getOneOrNullResult($filter->getHydrationMode());
    }

    public function getReference($entityName, $id) {
        return $this->getEntityManager()->getReference($entityName, $id);
    }

    public function clear($entityName = null) {
        $this->getEntityManager()->clear($entityName);
    }

}