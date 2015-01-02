<?php
namespace Ffs\Ffs\Application\Config;
use Ffs\Ffs\Application\Config;

class WebConfig extends Config {
    /**
     * @return Config|\Ffs\Ffs\Libs\Bag
     */
    public function getRequestRoutes() {
        return $this->get('routes')->get('request');
    }

    /**
     * @return Config|\Ffs\Ffs\Libs\Bag
     */
    public function getQueryRoutes() {
        return $this->get('routes')->get('query');
    }
}