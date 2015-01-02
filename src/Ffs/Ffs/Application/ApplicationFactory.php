<?php
namespace Ffs\Ffs\Application;

use Ffs\Ffs\Application\Config;
use Ffs\Ffs\Exception\Application\ApplicationEnvironmentUnknown;
use Ffs\Ffs\Request;
use Ffs\Ffs\Response\ViewModel;

/**
 * Class ApplicationFactory
 * @package Ffs\Ffs\Application
 */
class ApplicationFactory
{
    const VAL_WEB_ENVIRONMENT = 'Web';
    const VAL_CLI_ENVIRONMENT = 'Cli';
    const VAL_APPLICATION = 'Application';

    private $environments = [
        self::VAL_WEB_ENVIRONMENT, self::VAL_CLI_ENVIRONMENT
    ];

    protected $config = null;


    protected $applicationEnvironment = null;

    protected $responseHandler = null;

    protected $dbAdapter = null;

    /**
     * An application to run needs a configuration and a response handler.
     * The environment helps to determine which kind of application to build
     * The request handler will be send to the application on the run method
     *
     * @param Config $config
     * @param ViewModel $response
     * @param $environment
     * @param $appDir
     * @param $appPrefix
     * @param $appName
     */
    public function __construct(
        Config $config,
        ViewModel $response,
        $environment,
        $appDir,
        $corePrefix,
        $appPrefix,
        $appName
    )
    {
        $this->initializeConfiguration($config, $appDir, $corePrefix, $appPrefix, $appName);
        $this->setApplicationFromEnvironment($environment);
        $this->setPersistence();
        $this->responseHandler = $response;
    }

    /**
     * @param Config $config
     * @param $dir
     * @param $appPrefix
     * @param $appName
     * @throws \Ffs\Ffs\Exception\Application\ApplicationNotFoundException
     * @throws \Ffs\Ffs\Exception\Application\Config\InvalidConfigException
     */
    protected function initializeConfiguration(
        Config $config,
        $dir,
        $corePrefix,
        $appPrefix,
        $appName
    ) {
        $dirApp = $dir . DIRECTORY_SEPARATOR . $appName;
        $config->load($config->parse($dirApp));
        $config->set('dir', $dir);
        $config->set('dir-app', $dirApp);
        $config->set('namespace-app', $appPrefix);
        $config->set('namespace', $corePrefix);
        $config->set('application-name', $appName);
        $this->config = $config;
    }

    public function getConfig() {
        return $this->config;
    }

    public function setPersistence() {
        if ($this->getConfig()->has('persistence')) {
           $persistenceConfig = $this->getConfig()->get('persistence');
           $driver = $persistenceConfig->get('adapter');

           $driverAdapter = $this->getConfig()->get('namespace') .
               '\\Model\\Persistence\\' . ucfirst(strtolower($driver)) . 'Adapter';
           $this->dbAdapter = new $driverAdapter($persistenceConfig);
        }
    }

    public function setApplicationFromEnvironment($environment) {
        $this->verifySecureEnvironment($environment);
        $this->applicationEnvironment = $environment;
    }

    protected function verifySecureEnvironment($environment) {
        if (false === array_search($environment, $this->environments)) {
            throw new ApplicationEnvironmentUnknown($environment);
        }
    }

    public function create() {
        $applicationHandlerName = __NAMESPACE__ . '\\' .
            $this->applicationEnvironment . self::VAL_APPLICATION;
        return new $applicationHandlerName(
            $this->config, $this->responseHandler, $this->dbAdapter
        );


    }

}