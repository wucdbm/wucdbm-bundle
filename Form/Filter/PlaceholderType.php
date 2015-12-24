<?php

namespace Wucdbm\Bundle\WucdbmBundle\Form\Filter;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlaceholderType extends AbstractType {

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'mapped' => false,
            'required' => false,
            'attr' => array(
                'class' => 'placeholder',
                'placeholder' => 'Period: From - To'
            )
        ));
    }

    public function getParent() {
        return 'Symfony\Component\Form\Extension\Core\Type\TextType';
    }

}
