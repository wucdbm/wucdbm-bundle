<?php

namespace Wucdbm\Bundle\WucdbmBundle\Form\Filter;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EntityFilterType extends AbstractType {

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'attr' => [
                'class' => 'select2'
            ]
        ));
    }

    public function getParent() {
        return 'entity';
    }

}
