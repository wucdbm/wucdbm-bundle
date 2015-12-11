<?php

namespace Wucdbm\Bundle\WucdbmBundle\Form\Filter;

use Symfony\Component\Form\AbstractType;

abstract class BaseFilterType extends AbstractType {

    public function getParent() {
        return 'Wucdbm\Bundle\WucdbmBundle\Form\Filter\BasicFilterType';
    }

    public function getBlockPrefix() {
        return '';
    }

}