<?php

namespace Ffs\Ffs\Exception\Model;

use Ffs\Ffs\FfsException;

class InvalidModelDefinitionException extends FfsException {

    public function __construct(
        $message = null,
        $code = 0,
        FfsException $previous = null
    ) {
        return parent::__construct(
            "Every field off the module must be of a type extended from AbstractModuleType"
        );
    }
}