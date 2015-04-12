<?php

namespace Wucdbm\Bundle\WucdbmBundle\Form\DataTransformer;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\Exception\TransformationFailedException;

class EntityToIdTransformer implements ClassAwareDataTransformerInterface {

    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var string
     */
    protected $class;

    public function __construct(ObjectManager $objectManager, $class) {
        $this->objectManager = $objectManager;
        $this->class         = $class;
    }

    public function transform($entity) {
        if (null === $entity) {
            return;
        }

        return $entity->getId();
    }

    public function reverseTransform($id) {
        if (!$id) {
            return null;
        }

        $entity = $this->objectManager
            ->getRepository($this->class)
            ->find($id);

        if (null === $entity) {
            throw new TransformationFailedException();
        }

        return $entity;
    }
}