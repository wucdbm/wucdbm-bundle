<?php

namespace Wucdbm\Bundle\WucdbmBundle\Cache\Output;

use Symfony\Component\Console\Formatter\OutputFormatterInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wucdbm\Bundle\WucdbmBundle\Cache\Exception\CacheMissException;
use Wucdbm\Bundle\WucdbmBundle\Cache\Storage\AbstractStorage;

class CachedOutput implements OutputInterface {

    private $storage;

    private $key;

    private $ttl;

    private $verbosity;

    private $formatter;

    public function __construct(AbstractStorage $storage, $key, $ttl, $verbosity = self::VERBOSITY_NORMAL, $decorated = false, OutputFormatterInterface $formatter = null) {
        $this->storage = $storage;
        $this->key = $key;
        $this->ttl = $ttl;
        $this->verbosity = null === $verbosity ? self::VERBOSITY_NORMAL : $verbosity;
        $this->formatter = $formatter ?: new SimpleParagraphFormatter($decorated);
        $this->formatter->setDecorated($decorated);
    }

    /**
     * {@inheritdoc}
     */
    public function setFormatter(OutputFormatterInterface $formatter) {
        $this->formatter = $formatter;
    }

    /**
     * {@inheritdoc}
     */
    public function getFormatter() {
        return $this->formatter;
    }

    /**
     * {@inheritdoc}
     */
    public function setDecorated($decorated) {
        $this->formatter->setDecorated($decorated);
    }

    /**
     * {@inheritdoc}
     */
    public function isDecorated() {
        return $this->formatter->isDecorated();
    }

    /**
     * {@inheritdoc}
     */
    public function setVerbosity($level) {
        $this->verbosity = (int)$level;
    }

    /**
     * {@inheritdoc}
     */
    public function getVerbosity() {
        return $this->verbosity;
    }

    public function isQuiet() {
        return self::VERBOSITY_QUIET === $this->verbosity;
    }

    public function isVerbose() {
        return self::VERBOSITY_VERBOSE <= $this->verbosity;
    }

    public function isVeryVerbose() {
        return self::VERBOSITY_VERY_VERBOSE <= $this->verbosity;
    }

    public function isDebug() {
        return self::VERBOSITY_DEBUG <= $this->verbosity;
    }

    /**
     * {@inheritdoc}
     */
    public function writeln($messages, $type = self::OUTPUT_NORMAL) {
        $this->write($messages, true, $type);
    }

    /**
     * {@inheritdoc}
     */
    public function write($messages, $newline = false, $type = self::OUTPUT_NORMAL) {
        if (self::VERBOSITY_QUIET === $this->verbosity) {
            return;
        }

        $messages = (array)$messages;

        foreach ($messages as $message) {
            switch ($type) {
                case OutputInterface::OUTPUT_NORMAL:
                    $message = $this->formatter->format($message);
                    break;
                case OutputInterface::OUTPUT_RAW:
                    break;
                case OutputInterface::OUTPUT_PLAIN:
                    $message = strip_tags($this->formatter->format($message));
                    break;
                default:
                    throw new \InvalidArgumentException(sprintf('Unknown output type given (%s)', $type));
            }

            $this->doWrite($message, $newline);
        }
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