<?php

namespace Wucdbm\Bundle\WucdbmBundle\Form\Filter;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EntityFilterType extends AbstractType {

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'attr' => [
                'class' => 'select2'
            ]
        ));
    }

    public function getParent() {
        return 'entity';
    }

    /**
     * @return string
     */
    public function getName() {
        return 'wucdbm_filter_entity';
    }

}
