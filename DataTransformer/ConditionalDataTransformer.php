<?php

namespace Wucdbm\Bundle\WucdbmBundle\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class ConditionalDataTransformer implements DataTransformerInterface {

    /**
     * @var DataTransformerInterface
     */
    protected $transformer;

    /**
     * @var \Closure
     */
    protected $condition;

    public function __construct(DataTransformerInterface $transformer) {
        $this->transformer = $transformer;
    }

    public function transform($value) {
        return $this->transformer->transform($value);
    }

    public function reverseTransform($value) {
        return $this->transformer->transform($value);
    }

    public function isEligible($value) {
        return true;
    }

}