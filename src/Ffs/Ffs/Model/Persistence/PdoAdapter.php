<?php
namespace Ffs\Ffs\Model\Persistence;

use Ffs\Ffs\Application\Config;
use Ffs\Ffs\Exception\Model\FieldType\ValidationException;
use Ffs\Ffs\Libs\Bag;
use Ffs\Ffs\Model\AbstractFieldType;
use PDO;
use PDOStatement;

/**
 * Class PdoAdapter
 * @package Ffs\Ffs\Model\Persistence
 * @todo: inject db driver(pdo)
 */
class PdoAdapter implements PersistenceInterface {

    protected $db = null;
    protected $params = null;
    protected $statement = null;
    protected $paramsTypeMatch = [
        'StringFieldType' => PDO::PARAM_STR,
        'IntegerFieldType' => PDO::PARAM_INT,
        'EmailFieldType' => PDO::PARAM_STR,
    ];

    public function reset() {
        $this->statement = null;
        $this->params = new Bag();
        return $this;
    }

    public function addParamTypeMatch($yacsType, $pdoType) {
        $this->paramsTypeMatch[$yacsType] = $pdoType;
    }

    protected function addCustomParamTypeMatch() {
        $this->addParamTypeMatch('CommentFieldType', PDO::PARAM_STR);
        $this->addParamTypeMatch('NameFieldType', PDO::PARAM_STR);
    }
    public function __construct(Config $config) {
        //beware, hardcoded dependency
        $this->db = new PDO(
            $config->get('dsn'), $config->get('username'),
            $config->get('password')
        );
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->addCustomParamTypeMatch();
        $this->params = new Bag();
    }

    protected function prepareParams(PDOStatement $statement, $params) {

        foreach ($params as $paramName => $param) {
            $shortName = (new \ReflectionClass($param))->getShortName();
            $statement->bindParam(
                $paramName, $param->getValue(),
                $this->paramsTypeMatch[$shortName], $param->getMaxLength()
            );

        }
        return $statement;
    }

    public function execute($query)
    {
        $this->statement = $this->db->prepare($query);
        $this->statement = $this->prepareParams(
            $this->statement, $this->getParams()
        );

        $this->statement->execute();
    }

    public function fetchAll($query) {
        $this->statement = $this->db->prepare($query);
        $this->statement->execute($this->getParams()->all());
        return $this->statement->fetchAll();
    }

    public function addParam($name, AbstractFieldType $field) {
        if (!$field->isValid()) {
            throw new ValidationException($name);
        }
        $this->params->set($name, $field);
        return $this;
    }

    public function getParams() {
        return $this->params;
    }

    public function getParam($fieldName) {
        return $this->params[$fieldName];
    }

}

