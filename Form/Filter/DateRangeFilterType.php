<?php

namespace Wucdbm\WucdbmBundle\Form\Filter;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DateRangeFilterType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add($options['min_field_name'], 'hidden', array(
                'attr'   => array(
                    'class' => 'hidden min'
                )
            ))
            ->add($options['max_field_name'], 'hidden', array(
                'attr'   => array(
                    'class' => 'hidden max'
                )
            ))
            ->add('placeholder', 'filter_placeholder', array(
                'attr' => array(
                    'rel' => 'tooltip',
                    'title' => $options['placeholder'],
                    'placeholder' => $options['placeholder']
                )
            ))
        ;
    }

    public function buildView(FormView $view, FormInterface $form, array $options) {
        $view->vars['min_field_name'] = $options['min_field_name'];
        $view->vars['max_field_name'] = $options['max_field_name'];
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'inherit_data'   => true,
            'min_field_name' => 'date_min',
            'max_field_name' => 'date_max',
            'placeholder' => 'Period: From - To',
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'filter_date_range';
    }

}
