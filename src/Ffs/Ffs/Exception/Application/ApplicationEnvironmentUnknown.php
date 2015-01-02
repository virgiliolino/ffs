<?php
namespace Ffs\Ffs\Exception\Application;

use Ffs\Ffs\FfsException;

class ApplicationEnvironmentUnknown extends FfsException {
    public function __construct($message = null, $code = 0, FfsException $previous = null) {
        return parent::__construct('This kind of application environment cant be instantiated');
    }

}