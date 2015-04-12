<?php

namespace Wucdbm\Bundle\WucdbmBundle\Form\Filter;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FilterTextType extends AbstractType {

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
            'required' => false,
            'placeholder' => ''
        ));
    }

    public function buildView(FormView $view, FormInterface $form, array $options) {
        $view->vars = array_replace_recursive($view->vars, array(
            'attr' => array(
                'rel' => 'tooltip',
                'title' => $options['placeholder'],
                'placeholder' => $options['placeholder']
            )
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'wucdbm_filter_text';
    }

    public function getParent() {
        return 'text';
    }

}
