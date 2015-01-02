<?php
namespace Ffs\MyApp\Model;

use Ffs\Ffs\Model\FieldType\IntegerFieldType;
use Ffs\Ffs\Model\FieldType\EmailFieldType;
use Ffs\Ffs\Model\FieldType\StringFieldType;
use Ffs\Ffs\Model\WebModel;
use Ffs\MyApp\Model\FieldType\NameFieldType;

class PostsModel extends WebModel
{
    public function getDefinition() {
        $messageFieldType = new StringFieldType();
        $messageFieldType->setMinLength(4);
        $messageFieldType->setMaxLength(255);

        return [
            'name' => new NameFieldType(),
            'message' => $messageFieldType,
            'email' => new EmailFieldType(),
            'comments' => new IntegerFieldType(),
        ];
    }

    public function getPosts() {
        return $this->getPersistence()->fetchAll(
            'SELECT * FROM Posts ORDER BY id_post DESC LIMIT 100'
        );
    }

    public function addPost()
    {
        $this->getPersistence()
            ->addParam('name', $this->get('name'))
            ->addParam('email', $this->get('email'))
            ->addParam('message', $this->get('message'))
            ->execute(
                'INSERT INTO Posts SET name = :name, ' .
                'email = :email, message = :message, posttime = now()'
            );
    }
}
