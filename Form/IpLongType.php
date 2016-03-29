<?php

namespace Wucdbm\Bundle\WucdbmBundle\Form;

use Symfony\Component\Form\AbstractType as SymfonyAbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Wucdbm\Bundle\WucdbmBundle\Form\DataTransformer\Ip2LongTransformer;

class IpLongType extends SymfonyAbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $transformer = new Ip2LongTransformer();
        $builder->addModelTransformer($transformer);

        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();
            $ip = $event->getData();
            if (false === filter_var($ip, FILTER_VALIDATE_IP)) {
                $form->addError(new FormError(sprintf('Provided IP "%s" is not a valid IP Address', $ip)));
            }
        });
    }

    public function getParent() {
        return TextType::class;
    }

}