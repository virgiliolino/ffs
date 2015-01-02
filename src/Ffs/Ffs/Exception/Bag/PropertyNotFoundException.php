<?php

namespace Ffs\Ffs\Exception\Bag;

use Ffs\Ffs\FfsException;

class PropertyNotFoundException extends FfsException {

    public function __construct(
        $message = null,
        $code = 0,
        FfsException $previous = null
    ) {
        return parent::__construct('Property was not found');
    }
}