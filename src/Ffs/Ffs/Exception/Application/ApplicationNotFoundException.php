<?php
namespace Ffs\Ffs\Exception\Application;

use Ffs\Ffs\FfsException;

class ApplicationNotFoundException extends FfsException {
    public function __construct($message = null, $code = 0, FfsException $previous = null) {
        return parent::__construct('Application Not Found. Please check the directory of your application.');
    }

}