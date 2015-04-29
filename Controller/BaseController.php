<?php

namespace Wucdbm\Bundle\WucdbmBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

abstract class BaseController extends Controller {

    public function json($data = null, $status = 200, $headers = array()) {
        $headers = array_merge($headers, [
            'Content-Type' => 'application/json'
        ]);
        return new JsonResponse($data, $status, $headers);
    }

    public function witter($data = null, $status = 200, $headers = array()) {
        return $this->json(array('witter' => $data), $status, $headers);
    }

    public function replace($data = null, $status = 200, $headers = array()) {
        return $this->json(array('replace' => $data), $status, $headers);
    }

    public function append($data = null, $status = 200, $headers = array()) {
        return $this->json(array('append' => $data), $status, $headers);
    }

    public function prepend($data = null, $status = 200, $headers = array()) {
        return $this->json(array('prepend' => $data), $status, $headers);
    }

    public function mfp($data = null, $status = 200, $headers = array()) {
        return $this->json(array('mfp' => $data), $status, $headers);
    }

    public function injectHtml($data = null, $status = 200, $headers = array()) {
        return $this->json(array('injectHtml' => $data), $status, $headers);
    }

}