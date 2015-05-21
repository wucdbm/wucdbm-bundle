<?php

namespace Wucdbm\Bundle\WucdbmBundle\Form\Doctrine;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Wucdbm\Bundle\WucdbmBundle\Form\DataTransformer\ClassAwareDataTransformerInterface;

class EntityHiddenType extends AbstractType {

    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om) {
        $this->om = $om;
    }

    /**
     * Add the data transformer to the field setting the entity repository
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        /** @var ClassAwareDataTransformerInterface $entityTransformer */
        $entityTransformer = new $options['transformer']($this->om, $options['class']);
        $builder->addModelTransformer($entityTransformer);
    }

    /**
     * Require the entity repository option to be set on the field
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'transformer' => 'Wucdbm\Bundle\WucdbmBundle\Form\DataTransformer\EntityToIdTransformer'
        ));
        $resolver->setRequired(array(
            'class'
        ))->setDefaults(array(
            'invalid_message' => 'The entity does not exist.',
        ));
    }

    /**
     * Set the parent form type to hidden
     * @return string
     */
    public function getParent() {
        return 'hidden';
    }

    /**
     * @return string
     */
    public function getName() {
        return 'entity_hidden';
    }
}