<?php
namespace Ffs\Ffs\Model\FieldType;

use Ffs\Ffs\Model\AbstractFieldType;

class IntegerFieldType extends AbstractFieldType {
    public function isValid() {
        $valid = parent::isValid();
        return $valid && $this->value == (int)($this->value);
    }
}