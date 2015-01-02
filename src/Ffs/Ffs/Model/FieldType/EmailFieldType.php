<?php
namespace Ffs\Ffs\Model\FieldType;

class EmailFieldType extends StringFieldType {
    protected $emailPattern = "/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/";

    public function __construct($value = null)
    {
        parent::__construct($value);
        $this->setMaxLength(256);
    }

    public function isValid() {
        $valid = parent::isValid();
        return $valid && preg_match($this->emailPattern, $this->value);
    }

}