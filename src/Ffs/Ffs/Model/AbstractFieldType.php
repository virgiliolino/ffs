<?php
namespace Ffs\Ffs\Model;

Abstract class AbstractFieldType {
    protected $value = null;
    protected $maxLength = null;
    protected $minLength = null;

    public function __construct($value = null) {
        $this->value = $value;
   }


    public function getValue() {
        return $this->value;
    }

    public function setValue($value, $model = null) {
        $this->value = $this->clean($value);
        if ($model) {
            $this->value = $model->clean($value);
        }
    }

    protected function clean($value) {
        return $value;
    }

    public function __toString() {
        return (string)$this->value;
    }

    public function getMaxLength() {
        return $this->maxLength;
    }

    public function getMinLength() {
        return $this->minLength;
    }

    public function setMaxLength($maxLength) {
        $this->maxLength = $maxLength;
        return $this;
    }

    public function setMinLength($minLength) {
        $this->minLength = $minLength;
        return $this;
    }

    public function isValid() {
        $valid = true;

        if ($this->maxLength && (strlen($this->value) > $this->maxLength)) {
            $valid = false;
        }

        if ($this->minLength && ($this->minLength > strlen($this->value))) {
            $valid = false;
        }

        return $valid;
    }
}