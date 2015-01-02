<?php
namespace Ffs\Ffs\Exception\Application\Config;

use Ffs\Ffs\FfsException;

class InvalidConfigException extends FfsException {
    public function __construct(
        $message = null,
        $code = 0,
        FfsException $previous = null
    ) {
        return parent::__construct('There was an error parsing the config file');
    }

}