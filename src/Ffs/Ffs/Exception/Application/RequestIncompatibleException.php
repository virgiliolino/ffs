<?php
namespace Ffs\Ffs\Exception\Application;

use Ffs\Ffs\FfsException;

class RequestIncompatibleException extends FfsException {
    public function __construct(
        $message = null,
        $code = 0,
        FfsException $previous = null
    ) {
        return parent::__construct('Please check the interface of the request required by the application');
    }

}