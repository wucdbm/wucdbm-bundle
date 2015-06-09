<?php

namespace Wucdbm\Bundle\WucdbmBundle\Form\Filter;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BasicFilterType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $this->addLimitField($builder);

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($builder) {
            // This is to make sure that if the limit was not passed in the request,
            // the default value of the model would be used and shown when the form is rendered,
            // instead of showing 'All' in the select, but limiting to the default instead
            $data = $event->getData();
            if (!isset($data['limit'])) {
                $form = $event->getForm();
                $data['limit'] = $form->get('limit')->getData();
                $event->setData($data);
            }
        });
    }

    protected function addLimitField(FormBuilderInterface $builder) {
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
            'label'   => 'Number of items',
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
        return 'wucdbm_filter_basic';
    }

}
