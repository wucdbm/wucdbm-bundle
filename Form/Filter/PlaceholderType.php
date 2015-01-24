<?php

namespace Wucdbm\WucdbmBundle\Form\Filter;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PlaceholderType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'mapped' => false,
            'required' => false,
            'attr' => array(
                'class' => 'placeholder',
                'placeholder' => 'Period: From - To'
            )
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'filter_placeholder';
    }

    public function getParent() {
        return 'text';
    }

}
