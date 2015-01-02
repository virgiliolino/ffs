<?php
namespace Ffs\Ffs\Exception\Model\FieldType;

use Ffs\Ffs\FfsException;

class ValidationException extends FfsException {
    public function __construct(
        $message = null,
        $code = 0,
        FfsException $previous = null
    ) {
        return parent::__construct(
            "There was an error while validating the field: {$message}"
        );
    }

}