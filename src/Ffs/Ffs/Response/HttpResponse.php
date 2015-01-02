<?php
namespace Ffs\Ffs\Response;

use Ffs\Ffs\Libs\Bag;

class HttpResponse extends ViewModel {
    const VAL_RESPONSE_TYPE_HTML = 'HTML';
    const VAL_RESPONSE_TYPE_JSON = 'JSON';
    const VAL_RESPONSE_TYPE_XML = 'XML';

    protected $responseType;

    public function __construct(array $things = null) {
        parent::__construct();
        $this->responseType = self::VAL_RESPONSE_TYPE_HTML;
        $this->set('headers', new Bag());
        $this->set('values', new Bag());
    }

    public function clean($value) {
        return htmlentities($value);
    }


    public function setJson() {
        $this->responseType = self::VAL_RESPONSE_TYPE_JSON;
        $this->addHeader('Content-type: application/json');
    }

    public function isJson() {
        return $this->responseType === self::VAL_RESPONSE_TYPE_JSON;
    }

    public function isXml() {
        return $this->responsetype === self::VAL_RESPONSE_TYPE_XML;
    }

    public function isHtml() {
        return $this->responseType === self::VAL_RESPONSE_TYPE_HTML;
    }

    public function getResponseType() {
        return $this->getResponseType();
    }

    public function addHeader($header) {
        $this->get('headers')->add($header);
    }

    public function sendHeaders() {
        foreach ($this->get('headers')->all() as $headerContent) {
            header($headerContent);
        }
    }





}