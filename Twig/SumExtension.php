<?php

namespace Wucdbm\Bundle\WucdbmBundle\Twig;

use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class SumExtension extends \Twig_Extension {

    /**
     * @var ExpressionLanguage
     */
    protected $language;

    /**
     * @var PropertyAccessor
     */
    protected $accessor;

    public function __construct(ExpressionLanguage $language = null) {
        if (null === $language) {
            $language = new ExpressionLanguage();
        }
        $this->language = $language;
        $this->accessor = PropertyAccess::createPropertyAccessor();
    }

    public function getFilters() {
        return array(
            'sum'   => new \Twig_Filter_Method($this, 'sum'),
            'count' => new \Twig_Filter_Method($this, 'count')
        );
    }

    public function getFunctions() {
        return array(
            'sum'   => new \Twig_Function_Method($this, 'sum'),
            'count' => new \Twig_Function_Method($this, 'count')
        );
    }

    public function sum($arrayOrObject, $propertyPath = null, $expression = null) {
        $sum = 0;
        foreach ($arrayOrObject as $value) {
            $shouldSum = true;
            if (null !== $expression) {
                $shouldSum = $this->language->evaluate($expression, array(
                    'value' => $value
                ));
            }
            if ($shouldSum) {
                if ($propertyPath) {
                    $value = $this->accessor->getValue($value, $propertyPath);
                }
                $sum += $value;
            }
        }
        return $sum;
    }

    public function count($arrayOrObject, $expression = null) {
        $count = 0;
        foreach ($arrayOrObject as $value) {
            $shouldSum = true;
            if (null !== $expression) {
                $shouldSum = $this->language->evaluate($expression, array(
                    'value' => $value
                ));
            }
            if ($shouldSum) {
                $count++;
            }
        }
        return $count;
    }

    public function getName() {
        return 'wucdbm_sum';
    }

    public function getAlias() {
        return 'wucdbm_sum';
    }
}