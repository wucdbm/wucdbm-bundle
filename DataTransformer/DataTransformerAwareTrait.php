<?php

namespace Wucdbm\Bundle\WucdbmBundle\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

trait DataTransformerAwareTrait {

    private $_wucdbmDataTransformers = [];

    public function addDataTransformer($field, DataTransformerInterface $transformer) {
        $this->_wucdbmDataTransformers[$field][] = $transformer;
    }

    /**
     * @param $field
     * @return DataTransformerInterface|null
     */
    public function getDataTransformers($field) {
        return isset($this->_wucdbmDataTransformers[$field]) ? $this->_wucdbmDataTransformers[$field] : [];
    }

    public function removeDataTransformers($field) {
        $this->_wucdbmDataTransformers[$field] = [];
    }

    public function getAllDataTransformers() {
        return $this->_wucdbmDataTransformers;
    }

    public function removeAllDataTransformers() {
        $this->_wucdbmDataTransformers = [];
    }

    public function setDataTransformers($transformers) {
        $this->_wucdbmDataTransformers = $transformers;
    }

}