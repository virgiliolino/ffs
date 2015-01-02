<?php
namespace Ffs\Ffs\Libs;

use Ffs\Ffs\Exception\Bag\ElementNotFoundException;
use ArrayIterator;
use Ffs\Ffs\Exception\Bag\InvalidKeyException;

/**
 * Class Bag
 * @package Ffs\Ffs\Libs
 */
class Bag implements \IteratorAggregate, \Countable{
    private $things;

    /**
     * @param array $things
     */
    public function __construct(array $things = null) {
        $this->things = $things;
    }

    /**
     * @return array
     */
    public function all() {
        return $this->things;
    }

    /**
     * @return array
     */
    public function keys() {
        return array_keys($this->things);
    }

    /**
     * @param array $things
     */
    public function replace(array $things = null) {
        $this->things = $things;
    }

    /**
     * @param $something
     */
    public function add($something) {
        $this->things[] = $something;
    }

    /**
     * @param $key
     * @return bool
     */
    public function has($key) {
        return isset($this->things[$key]);
    }

    /**
     * @param $key
     * @return Bag
     * @throws ElementNotFoundException
     * @throws InvalidKeyException
     */
    public function get($key) {
        if (is_object($key) || is_array($key)) {
            throw new InvalidKeyException(gettype($key));
        }
        if (!$this->has($key)) {
            throw new ElementNotFoundException($key);
        }
        $element = $this->things[$key];
        if (is_array($element)) {
            $element = new Bag($element);
        }
        return $element;
    }

    /**
     * @param $key
     * @param $value
     */
    public function set($key, $value) {
        $this->things[$key] = $value;
    }

    /**
     * @param $something
     */
    public function remove($something) {
        if ($this->has($something)) {
            unset($something);
        }
    }

    /**
     * @return ArrayIterator|\Traversable
     */
    public function getIterator() {
        return new ArrayIterator($this->all());
    }

    /**
     * @return int
     */
    public function count() {
        return count($this->things);
    }

}