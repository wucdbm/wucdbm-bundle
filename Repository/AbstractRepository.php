<?php

namespace Wucdbm\Bundle\WucdbmBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Wucdbm\Bundle\WucdbmBundle\Filter\AbstractFilter;

class AbstractRepository extends EntityRepository {

    /**
     * @param string $alias
     * @param null $indexBy
     * @return QueryBuilder
     */
    public function createQueryBuilder($alias, $indexBy = null) {
        return $this->_em->createQueryBuilder()
                         ->select($alias)
                         ->from($this->_entityName, $alias, $indexBy);
    }

    /**
     * @param QueryBuilder $builder
     * @param AbstractFilter $filter
     * @param $groupBy
     *
     * @return array|Paginator
     */
    public function returnFilteredEntities(QueryBuilder $builder, AbstractFilter $filter, $groupBy) {
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

        $builder->groupBy($groupBy);
        $query    = $builder->getQuery();
        $entities = $query->getResult($filter->getHydrationMode());
        return $entities;
    }

}