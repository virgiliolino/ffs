<?php
namespace Ffs\MyApp\Controller;

use Ffs\Ffs\Controller\AbstractController;
use Ffs\Ffs\Exception\Model\FieldType\ValidationException;
use \Exception;

class PostsController extends AbstractController {

    /**
     * ajax request. will display a list of posts
     * @param $query
     */
    public function listQuery($query) {
        $model = $this->getModel();
        $model->initializeDefinition($query);
        $response = $this->getApplication()->getResponse();
        try {
            $posts = $model->getPosts();
            //dirty solution to obfuscate emails
            foreach ($posts as $index => $post) {
                $posts[$index]['email'] = $this->encodeEmail($post['email']);
            }
            $response->addValue('posts', $posts);
            $response->addValue('route', 'comments');
        } catch (Exception $e) {

        }
        $this->getView()->setTemplate('_posts')
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
     * @param $query
     */
    public function mainQuery($query) {
        $response = $this->getApplication()->getResponse();
        $response->addValue('title', 'yacs - Posts');
        $response->addValue('method', 'post');
        $this->getView()->display($response);
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
            $model->addPost();
        } catch (ValidationException $e) {
            $response->setError($e);
        }
        $this->getView()->display($response, false);

    }
}
