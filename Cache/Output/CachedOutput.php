<?php

namespace Wucdbm\Bundle\WucdbmBundle\Cache\Output;

use Symfony\Component\Console\Formatter\OutputFormatterInterface;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Console\Output\OutputInterface;
use Wucdbm\Bundle\WucdbmBundle\Cache\Exception\CacheMissException;
use Wucdbm\Bundle\WucdbmBundle\Cache\Storage\AbstractStorage;

/**
 * @deprecated use \Wucdbm\Bundle\WucdbmBundle\Output\CachedOutput instead
 */
class CachedOutput extends Output {

    /** @var AbstractStorage  */
    private $storage;

    /** @var string */
    private $key;

    /** @var int */
    private $ttl;

    public function __construct(AbstractStorage $storage, $key, $ttl, $verbosity = self::VERBOSITY_NORMAL, $decorated = false, OutputFormatterInterface $formatter = null) {
        parent::__construct($verbosity, $decorated, $formatter);
        $this->storage = $storage;
        $this->key = $key;
        $this->ttl = $ttl;
    }

    /**
     * {@inheritdoc}
     */
    protected function doWrite($message, $newline) {
        $message = $message . ($newline ? PHP_EOL : '');
        try {
            $current = $this->storage->get($this->key);
            $current .= $message;
            $this->storage->set($this->key, $current, $this->ttl);
        } catch (CacheMissException $e) {
            $this->storage->set($this->key, $message, $this->ttl);
        }
    }

}