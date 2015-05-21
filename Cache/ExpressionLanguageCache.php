<?php

namespace Wucdbm\Bundle\WucdbmBundle\Cache;

use Symfony\Component\ExpressionLanguage\ParsedExpression;
use Symfony\Component\ExpressionLanguage\ParserCache\ParserCacheInterface;
use Wucdbm\Bundle\WucdbmBundle\Cache\Storage\AbstractStorage;

class ExpressionLanguageCache implements ParserCacheInterface {

    /**
     * @var string
     */
    protected $prefix;

    /**
     * @var int
     */
    protected $expire;

    /**
     * @var AbstractStorage
     */
    protected $storage;

    public function __construct(AbstractStorage $storage, $expire = 3600, $prefix = '') {
        $this->storage = $storage;
        $this->expire  = $expire;
        $this->prefix  = $prefix;
    }

    public function save($key, ParsedExpression $expression) {
        $key = $this->getKey($key);
        $this->storage->set($key, $expression, $this->expire, true);
    }

    public function fetch($key) {
        $key = $this->getKey($key);
        return $this->storage->get($key, false);
    }

    protected function getKey($key) {
        return $this->storage->generatekey($this->prefix, $key);
    }

}