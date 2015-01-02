<?php
namespace Ffs\Ffs\Request;

use Ffs\Ffs\Libs\Bag;

class HttpRequest extends AbstractRequest {

    public function __construct(array $things = null) {
        parent::__construct([
            'query' => new Bag($_GET),
            'request' => new Bag($_POST),
            'server' => new Bag($_SERVER),
            'cookie' => new Bag($_COOKIE),
        ]);
    }

    public function getRequest() {
        return $this->get('request');
    }

    public function getQuery() {
        return $this->get('query');
    }

    public function getServer() {
        return $this->get('server');
    }

    public function getCookie() {
        return $this->get('cookie');
    }
}