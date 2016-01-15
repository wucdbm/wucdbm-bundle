<?php

namespace Wucdbm\Bundle\WucdbmBundle\Twig;

class DateExtension extends \Twig_Extension {

    public function getFilters() {
        return [
            new \Twig_SimpleFilter('date_diff', [$this, 'date_diff'])
        ];
    }

    public function date_diff($date1, $date2) {
        $date1 = $this->normalizeDate($date1);
        $date2 = $this->normalizeDate($date2);

        return $date1->diff($date2);
    }

    protected function normalizeDate($date) {
        if ($date instanceof \DateTime) {
            return $date;
        }

        return new \DateTime($date);
    }

    public function getName() {
        return 'wucdbm_date';
    }

}