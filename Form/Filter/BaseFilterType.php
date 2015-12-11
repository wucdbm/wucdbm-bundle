<?php

namespace Wucdbm\Bundle\WucdbmBundle\Form\Filter;

abstract class BaseFilterType {

    public function getParent() {
        return 'Wucdbm\Bundle\WucdbmBundle\Form\Filter\BasicFilterType';
    }

    public function getBlockPrefix() {
        return '';
    }

}