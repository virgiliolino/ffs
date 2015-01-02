<?php
namespace Ffs\Ffs\Application;

use Ffs\Ffs\Exception\Application\RequestIncompatibleException;
use Ffs\Ffs\Libs\Bag;
use Ffs\Ffs\Request\HttpRequest;
use Ffs\Ffs\Request\Request;
use Ffs\Ffs\Request\RequestInterface;
use Ffs\Ffs\Request\AbstractRequest;

class WebApplication extends AbstractApplication {
    const KEY_METHOD_IDENTIFIER = 'REQUEST_METHOD';
    const VAL_METHOD_QUERY = 'GET';
    const VAL_METHOD_REQUEST = 'POST';
    const VAL_DEFAULT_ACTION = 'main';
    const VAL_ACTION_NAME_QUERY = 'Query';
    const VAL_ACTION_NAME_REQUEST = 'Request';
    const VAL_CONTROLLER_NAME = 'Controller';

    protected $request = null;

    protected $response = null;

    public function run(AbstractRequest $request) {

        if (!$request instanceOf HttpRequest) {
            throw new RequestIncompatibleException;
        }
        $this->request = $request;

        $this->handleRequest();
    }

    /**
     * helper method to check if the request is a get
     *
     * @return bool
     */
    public function isGet() {
        return $this->request->getServer()->get(self::KEY_METHOD_IDENTIFIER)
        === self::VAL_METHOD_QUERY;
    }

    /**
     * helper method to check if the request is a post
     *
     * @return bool
     */
    public function isPost() {
        return $this->request->getServer()->get(self::KEY_METHOD_IDENTIFIER)
        === self::VAL_METHOD_REQUEST;
    }

    protected function handleRequest() {
        if ($this->isPost()) {
            $request = $this->request->getRequest();
            if ($this->request->getQuery()->has('action')) {
                $action = $this->request->getQuery()->get('action');
                $request->set('action', $action);
            }
            $routes = $this->getConfig()->getRequestRoutes();
            $action = self::VAL_ACTION_NAME_REQUEST;
        }

        if ($this->isGet()) {
            $request = $this->request->getQuery();
            $routes = $this->getConfig()->getQueryRoutes();
            $action = self::VAL_ACTION_NAME_QUERY;
        }

        $this->matchAction($request, $routes, $action);
    }


    /**
     * Forward the request to the right controller
     *
     * @param $request
     * @param $queryRoutes
     */
    protected function matchAction($request, $queryRoutes, $action) {
        //the request is on the action get param
        //else we get a default action from config
        $requestParts = $request->has('action') ?
            $request->get('action') : $queryRoutes->get('default');

        //the action is on the form controller/method
        $requestParts = explode('/', $requestParts);
        $domain = ucfirst($requestParts[0]);
        $controllerName = $this->getConfig()->get('namespace-app') .
            '\\Controller\\' . $domain
            . self::VAL_CONTROLLER_NAME;

        //if there is no method, we use the default one
        $method = count($requestParts) > 1 && $requestParts[1] ?
            $requestParts[1] : self::VAL_DEFAULT_ACTION;
        $method = strtolower($method) . $action;

        //the action is no needed anymore
        $this->request->remove('action');
        $controller = new $controllerName($this, $domain);
        $controller->$method($request);
    }

}