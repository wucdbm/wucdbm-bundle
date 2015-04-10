<?php

namespace Wucdbm\Bundle\WucdbmBundle\Form\DataTransformer;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;

interface ClassAwareDataTransformerInterface extends DataTransformerInterface {

    public function __construct(ObjectManager $objectManager, $class);

}