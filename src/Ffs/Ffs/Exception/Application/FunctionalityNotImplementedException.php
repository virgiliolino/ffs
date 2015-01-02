<?php
namespace Ffs\Ffs\Exception\Application;

use Ffs\Ffs\FfsException;

class FunctionalityNotImplementedException extends FfsException {
    public function __construct(
        $message = null,
        $code = 0,
        FfsException $previous = null
    ) {
        return parent::__construct("The method:{$message} is not implemented on this version");
    }

}