<?php

namespace Ffs\Ffs\Exception\Model;

use Ffs\Ffs\FfsException;

class DBMSIncompatibleException extends FfsException {

    public function __construct(
        $message = null,
        $code = 0,
        FfsException $previous = null
    ) {
        return parent::__construct(
            'Youre trying to instantiate a DB with the wrong class'
        );
    }
}