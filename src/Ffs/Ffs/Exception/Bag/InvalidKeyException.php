<?php

namespace Ffs\Ffs\Exception\Bag;

use Ffs\Ffs\FfsException;

class InvalidKeyException extends FfsException {

    public function __construct(
        $message = null,
        $code = 0,
        FfsException $previous = null
    ) {
        return parent::__construct(
            "You sent a {$message} as key. This is not possible"
        );
    }
}