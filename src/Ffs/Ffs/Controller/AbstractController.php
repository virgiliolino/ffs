<?php
namespace Ffs\Ffs\Controller;

use Ffs\Ffs\Application\AbstractApplication;

/**
 * Class AbstractController
 * @package Ffs\Ffs\Controller
 */
Abstract class AbstractController {
    protected $application;
    protected $domain;

    /**
     * @param AbstractApplication $application
     * @param $domain
     */
    public function __construct(AbstractApplication $application, $domain) {
        $this->application = $application;
        $this->domain = $domain;
    }

    /**
     * @return AbstractApplication
     */
    public function getApplication() {
        return $this->application;
    }

    /**
     * @return mixed
     */
    public function getDomain() {
        return $this->domain;
    }

    /**
     * @return \Ffs\Ffs\Application\Config|null
     */
    public function getConfig() {
        return $this->getApplication()->getConfig();
    }

    /**
     * @param null $params
     * @return mixed
     */
    public function getModel($params = null) {
        $modelHandlerName = $this->getConfig()->get('namespace-app') .
            '\\Model\\' . $this->domain . 'Model';
        $modelHandler = new $modelHandlerName($params);
        $modelHandler->setPersistence($this->application->getPersistence());
        return $modelHandler;
    }

    public function getView() {
        $viewHandler = $this->getConfig()->get('namespace-app') .
            '\\View\\' . $this->domain . 'View';
        return new $viewHandler(
            $this->getDomain(),
            $this->getApplication()->getConfig()->get('dir-app')
        );
    }
}