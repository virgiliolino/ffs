<?php
namespace Ffs\Ffs\Model\Persistence;

use Ffs\Ffs\Model\AbstractFieldType;

interface PersistenceInterface {
    public function fetchAll($query);
    public function execute($query);
    public function addParam($name, AbstractFieldType $field);
    public function getParams();
    public function getParam($fieldName);
}