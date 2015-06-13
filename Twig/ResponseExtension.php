<?php

namespace Wucdbm\Bundle\WucdbmBundle\Twig;

use Symfony\Component\HttpFoundation\Response;

class ResponseExtension extends \Twig_Extension {

    public function getFilters() {
        return array(
            'responseStatus' => new \Twig_Filter_Method($this, 'responseStatus')
        );
    }

    public function responseStatus($code) {
        if (isset(Response::$statusTexts[$code])) {
            return Response::$statusTexts[$code];
        }
        return 'No status text for code ' . $code;
    }

    public function getName() {
        return 'wucdbm_response';
    }

    public function getAlias() {
        return 'wucdbm_response';
    }
}