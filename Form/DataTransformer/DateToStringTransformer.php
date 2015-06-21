<?php

namespace Wucdbm\Bundle\WucdbmBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class DateToStringTransformer implements DataTransformerInterface {

    /**
     * @var string
     */
    protected $format;

    public function __construct($format) {
        $this->format = $format;
    }

    /**
     * @param \DateTime|null $date
     * @return string
     */
    public function transform($date) {
        if (null === $date) {
            return '';
        }
        return $date->format($this->format);
    }

    /**
     * @param  string $dateString
     * @return \DateTime
     */
    public function reverseTransform($dateString) {
        $date = \DateTime::createFromFormat($this->format, $dateString);
        $date->setTime(0, 0, 0);
        return $date;
    }
}