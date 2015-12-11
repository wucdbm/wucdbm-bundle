<?php

namespace Wucdbm\Bundle\WucdbmBundle\Twig;

use Symfony\Component\HttpFoundation\Response;

class ResponseExtension extends \Twig_Extension {

    public function getFilters() {
        return [
            new \Twig_SimpleFilter('responseStatus', [$this, 'responseStatus'])
        ];
    }

    public function responseStatus($code) {
        if (isset(Response::$statusTexts[$code])) {
            return Response::$statusTexts[$code];
        }

        return sprintf('Status Code %s', $code);
    }

    public function getName() {
        return 'wucdbm_response';
    }

}