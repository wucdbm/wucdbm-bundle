<?php

namespace Wucdbm\Bundle\WucdbmBundle\Output;

use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Formatter\OutputFormatterInterface;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Console\Output\OutputInterface;

class CondensedOutput extends Output {

    /**
     * @var OutputInterface
     */
    protected $outputs;

    public function __construct(array $outputs, $verbosity = self::VERBOSITY_NORMAL, $decorated = false, OutputFormatterInterface $formatter = null) {
        parent::__construct($verbosity, $decorated, $formatter);
        $this->outputs = $outputs;
    }

    /**
     * {@inheritdoc}
     */
    public function writeln($messages, $options = self::OUTPUT_NORMAL) {
        /** @var OutputInterface $output */
        foreach ($this->outputs as $output) {
            $output->writeln($messages, $options);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function write($messages, $newline = false, $options = self::OUTPUT_NORMAL) {
        /** @var OutputInterface $output */
        foreach ($this->outputs as $output) {
            $output->write($messages, $newline, $options);
        }
    }

    protected function doWrite($message, $newline) {
        // nothing here, writing is done by the wrapped outputs
    }

}