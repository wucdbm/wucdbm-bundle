<?php

namespace Wucdbm\Bundle\WucdbmBundle\Cache\Output;

use Symfony\Component\Console\Formatter\OutputFormatterInterface;
use Symfony\Component\Console\Formatter\OutputFormatterStyleInterface;

class SimpleParagraphFormatter implements OutputFormatterInterface {

    private $decorated;

    public static function escape($text) {
        return htmlspecialchars($text);
    }


    /**
     * @param bool|false $decorated
     */
    public function __construct($decorated = false) {
        $this->decorated = (bool)$decorated;
    }

    /**
     * @param bool $decorated
     */
    public function setDecorated($decorated) {
        $this->decorated = (bool)$decorated;
    }

    /**
     * @return mixed
     */
    public function isDecorated() {
        return $this->decorated;
    }

    /**
     * Formats a message according to the given styles.
     *
     * @param string $message The message to style
     *
     * @return string The styled message
     *
     * @api
     */
    public function format($message) {
        $message = (string)$message;
        if ($this->isDecorated()) {
            return '<p>' . $message . '</p>';
        }

        return $message;
    }

    public function setStyle($name, OutputFormatterStyleInterface $style) {
        //
    }

    public function hasStyle($name) {
        return false;
    }

    public function getStyle($name) {
        return null;
    }

}
