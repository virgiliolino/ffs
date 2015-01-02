<?php
namespace Ffs\Ffs\Model\Persistence;

use Ffs\Ffs\Application\Config;

class MysqlAdapter extends PdoAdapter implements PersistenceInterface {
    public function __construct(Config $config) {
        //beware, hardcoded dependency
        $host = $config->get('host');
        $name = $config->get('name');
        $config->set('dsn', "mysql:host={$host};dbname={$name}");
        parent::__construct($config);
    }
}