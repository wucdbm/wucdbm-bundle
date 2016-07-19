<?php

namespace Wucdbm\Bundle\WucdbmBundle\Repository;

use Doctrine\ORM\EntityRepository;

class AbstractRepository extends EntityRepository {

    use WucdbmRepositoryTrait;

}