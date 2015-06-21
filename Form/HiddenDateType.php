<?php

namespace Wucdbm\Bundle\WucdbmBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Wucdbm\Bundle\WucdbmBundle\Form\DataTransformer\DateToStringTransformer;

class HiddenDateType extends AbstractType {

    public function __construct() {
    }

    public function getName() {
        return 'wucdbm_hidden_date';
    }

    public function getParent() {
        return 'hidden';
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $transformer = new DateToStringTransformer($options['format']);
        $builder->addModelTransformer($transformer);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults([
            'format' => 'Y-m-d'
        ]);
    }
}