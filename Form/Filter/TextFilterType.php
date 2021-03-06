<?php

namespace Wucdbm\Bundle\WucdbmBundle\Form\Filter;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TextFilterType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'required'    => false,
            'placeholder' => ''
        ));
    }

    public function buildView(FormView $view, FormInterface $form, array $options) {
        $view->vars = array_replace_recursive($view->vars, array(
            'attr' => array(
                'rel'         => 'tooltip',
                'title'       => $options['placeholder'],
                'placeholder' => $options['placeholder']
            )
        ));
    }

    public function getParent() {
        return 'Symfony\Component\Form\Extension\Core\Type\TextType';
    }

}
