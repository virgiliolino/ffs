<?php
namespace Ffs\MyApp\Controller;

use Ffs\Ffs\Controller\AbstractController;
use Ffs\Ffs\Exception\Model\FieldType\ValidationException;
use \Exception;

class CommentsController extends AbstractController {
    /**
     * @param $query
     */
    public function mainQuery($query) {
        $model = $this->getModel();
        $model->initializeDefinition($query);
        $response = $this->getApplication()->getResponse();
        try {
            $comments = $model->getComments();
            //dirty solution to obfuscate emails
            foreach ($comments as $index => $comment) {
                $comments[$index]['email'] =
                    $this->encodeEmail($comments[$index]['email']);
            }
            $response->addValue('comments', $comments);
            $response->addValue('route', 'comments');
        } catch (Exception $e) {

        }
        $this->getView()->setTemplate('_comments')
            ->display($response);
    }

    /**
     * the way fields should be rendered should be handled
     * automatically by a display method in the model
     * @param $email
     * @return string
     */
    protected function encodeEmail($email) {
    $hex='';
    for ($i=0; $i < strlen($email); $i++)
    {
        $hex .= '%'.dechex(ord($email[$i]));
    }
    return $hex;
}
    /**
     * @param $request
     */
    public function mainRequest($request) {
        $response = $this->getApplication()->getResponse();
        $response->setJson();
        $model = $this->getModel();
        $model->initializeDefinition($request);
        try {
            $model->addComment();
        } catch (ValidationException $e) {
            $response->setError($e);
        }
        $this->getView()->display($response, false);

    }
}
