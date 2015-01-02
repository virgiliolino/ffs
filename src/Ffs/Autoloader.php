<?php
namespace Ffs;

use \Exception;

/**
 * Class Autoloader
 * @package Ffs
 */
class Autoloader {
    protected $appPrefix = null;
    protected $prefixLen = null;
    protected $appDir = null;
    protected $appDirLen = null;

    /**
     * @return null|string
     */
    public function getAppDir() {
        return $this->appDir;
    }

    /**
     * @return null|string
     */
    public function getAppPrefix() {
        return $this->appPrefix;
    }

    public function __construct()
    {
        $this->appDir = __DIR__;
        $this->appPrefix =  __NAMESPACE__;
        $this->prefixLen = strlen($this->appPrefix);
        spl_autoload_register(array($this, 'loadClass'));
    }

    /**
     * @param $class
     * @throws Exception
     */
    public function loadClass($class) {
        if (strpos($class, $this->appPrefix) !== 0) {
             throw new Exception(
                'autoloader multiple namespace registrations not possible on' .
                ' yacs v0.1'
            );
        }
        $relative_class = substr($class, $this->prefixLen);

        $file = $this->appDir . str_replace('\\', '/', $relative_class) . '.php';
        if (file_exists($file)) {


            require $file;
        } else {echo "file:$file noexist";}
    }
}
