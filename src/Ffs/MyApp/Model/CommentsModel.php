<?php
namespace Ffs\MyApp\Model;

use Ffs\Ffs\Model\FieldType\IntegerFieldType;

use Ffs\Ffs\Model\FieldType\EmailFieldType;
use Ffs\Ffs\Model\FieldType\StringFieldType;
use Ffs\Ffs\Model\WebModel;
use Ffs\MyApp\Model\FieldType\NameFieldType;

class CommentsModel extends WebModel
{
    public function getDefinition() {
        $messageFieldType = new StringFieldType();
        $messageFieldType->setMinLength(4);
        $messageFieldType->setMaxLength(255);

        return [
            'id_comment' => new IntegerFieldType(),
            'name' => new NameFieldType(),
            'message' => $messageFieldType,
            'email' => new EmailFieldType(),
            'id_post' => new IntegerFieldType(),
        ];
    }

    public function getComments() {
        return $this->getPersistence()
            ->addParam('id_post', $this->get('id_post'))
            ->fetchAll(
                'SELECT * FROM Comments WHERE id_post = :id_post
                 ORDER BY id_comment DESC LIMIT 100'
            );
    }

    /**
     * @throws \Ffs\Ffs\Exception\Bag\ElementNotFoundException
     * @throws \Ffs\Ffs\Exception\Bag\InvalidKeyException
     * @todo: working directly with another post, just sending the query.
     * we should be able to make $this->getModel('posts')->incComments();
     */
    public function addComment()
    {
        $this->getPersistence()
            ->addParam('name', $this->get('name'))
            ->addParam('email', $this->get('email'))
            ->addParam('message', $this->get('message'))
            ->addParam('id_post', $this->get('id_post'))
            ->execute(
                'INSERT INTO Comments SET name = :name, ' .
                'email = :email, message = :message, id_post = :id_post, commenttime = now()'
            );

        //this call should be: $this->getModel('Post')->increaseComments(postId);
        $this->getPersistence()
            ->reset()
            ->addParam('id_post', $this->get('id_post'))
            ->execute(
                'UPDATE Posts SET comments = comments + 1 WHERE id_post = :id_post'
            );
    }
}
