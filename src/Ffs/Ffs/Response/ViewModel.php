<?php
namespace Ffs\Ffs\Response;

use Ffs\Ffs\Libs\Bag;
use Ffs\Ffs\FfsException;

Abstract class ViewModel extends Bag {
    public function __construct(array $things = null) {
        parent::__construct($things);
        $this->set('values', new Bag());
    }

    abstract public function clean($value);

    public function addValue($name, $value) {
        $this->get('values')->set($name, $value);
    }

    public function getValue($name) {
        return $this->get('values')->get($name);
    }

    public function setError(FfsException $e) {
        $this->set('error', $e);
        $this->set('errorCode', $e->getCode());
        $this->set('errorMessage', $e->getMessage());
        return $this;
    }

    public function isError() {
        return $this->has('error');
    }

    public function isSuccess() {
        return !$this->has('error');
    }

    public function getError() {
        return $this->has('error') ?
            $this->get('error') : false;
    }

    public function getErrorMessage() {
        return $this->has('errorMessage') ?
            $this->get('errorMessage') : false;
    }

    public function getErrorCode() {
        return $this->has('errorCode') ?
            $this->get('errorCode') : false;
    }
}
