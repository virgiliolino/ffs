<?php

namespace Ffs\Ffs\Exception\Bag;

use Ffs\Ffs\FfsException;

class ElementNotFoundException extends FfsException {

    public function __construct(
        $message = null,
        $code = 0,
        FfsException $previous = null
    ) {
        return parent::__construct("{$message} was not found on the bag");
    }
}