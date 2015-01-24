<?php

namespace Wucdbm\Bundle\WucdbmBundle\Form\Filter;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BasicFilterType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('limit', 'choice', array(
            'choices' => array(
                0    => 'All',
                10   => '10 Results',
                20   => '20 Results',
                50   => '50 Results',
                100  => '100 Results',
                250  => '250 Results',
                500  => '500 Results',
                1000 => '1000 Results'
            ),
            //            'empty_value' => 'Всички резултати',
            //            'empty_data'  => null,
            'label'   => 'Брой резултати',
            'attr'    => array(
                'class' => 'select2'
            )
        ));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'attr'               => array(
                'class' => 'autosubmit filter-form'
            ),
            'method'             => 'GET',
            'csrf_protection'    => false,
            'allow_extra_fields' => true
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'basic_filter_type';
    }

}
