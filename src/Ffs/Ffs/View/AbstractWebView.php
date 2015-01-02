<?php
namespace Ffs\Ffs\View;
use Ffs\Ffs\Response\ViewModel;

/**
 * Class AbstractWebView
 * @package Ffs\Ffs\View
 * @todo: extract parser concern into a new class, this include
 * also handleHtml...handleJson
 */
Abstract class AbstractWebView
    extends AbstractView
{

    protected function handleHtmlDisplay($response) {
        if (null === $this->getTemplate()) {
            $template = $this->domain;
            $this->setTemplate($template);
        }
        $this->viewModel = $response;
        $this->view = file_get_contents(
            $this->getTemplateDir() . $this->getTemplate() . '.html'
        );
        $this->handleBlocks();
        return $this->parseTemplate($this->view);
    }

    protected function handleJsonDisplay($response) {
        if ($response->isSuccess()) {
            $responseContent = ['result' => 'OK'];
        }

        if ($response->isError()) {
            $responseContent = ['result' => 'ERROR'];
        }

        if ($response->getErrorMessage()) {
            $responseContent['errorMessage'] = $response->getErrorMessage();
        }

        if ($response->getErrorCode()) {
            $responseContent['errorCode'] = $response->getErrorCode();
        }
        $response->sendHeaders();
        echo json_encode($responseContent);
    }

    protected function handleXmlDisplay() {
        throw new FunctionalityNotImplementedException();
    }

    protected function handleContent($response, $responseContent) {
    }

    /**
     * @param ViewModel $response
     * @todo: handle xml
     */
    public function display(ViewModel $response) {
        if ($response->isJson()) {
            $responseContent = $this->handleJsonDisplay($response);
        }

        if ($response->isHtml()) {
            $responseContent = $this->handleHtmlDisplay($response);
        }

        echo $responseContent;
    }

    protected function handleBlocks() {
        preg_match_all(
            "/{{start (.+?) (.+?)}}/", $this->view, $matches, PREG_OFFSET_CAPTURE
        );
        foreach ($matches[0] as $matchIndex => $startingBlocks) {
            $this->parseBlocks($startingBlocks, $matches[2][$matchIndex][0]);
        }
    }

    protected function parseLink($match) {
echo '<hr>'.print_r($match, true).'<hr>';
//        if ($match[2] != 'http://' && $match[2] != 'https://') {
  //         $match[0] = 'http://' . $match[0];
    //    }

        return "<a href=\"{$match[0]}\" target=\"blank\">{$match[0]}</a>";
    }
    /**
     * @param $view
     * @return mixed
     * @todo: inject also the viewModel
     */
    protected function parseTemplate($view) {
        $view = preg_replace_callback(
            "/{(.+?)}/", array( &$this, 'parseValues'), $view
        );

        $view = preg_replace_callback(
            "/{{(.+?) (.+?)}}/", array( &$this, 'parseTag'), $view
        );

/*        $view = preg_replace_callback(
            '#\b(([\w-]+://?|www[.])[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/)))#iS', array( &$this, 'parseLink'), $view
        );
*/
        return $view;
    }

    protected function getSubviewKeys($view) {
        list($keys[],) = each($view);
        while ($devNull = each($view)) {
            list($keys[],) = each($view);
        }
        return $keys;
    }

    /**
     * at the moment i just fill the values of the
     * subview on the main view
     * @param $iterable
     * @param $keys
     * @todo: . we should create a subview and send it
     */
    protected function populateSubview($iterable, $keys) {
        foreach ($keys as $key) {
            if (isset($iterable[$key])) {
                $this->viewModel->addValue($key, $iterable[$key]);
            }
        }
    }

    /**
     * remove all keys populated before to render the subview.
     * when we implement the todo of method populatesubview
     * this method is not needed anymore
     * @param keys
     */
    protected function cleanSubview($keys) {
        foreach ($keys as $key) {
            $this->viewModel->remove($key);
        }
    }

    protected function parseBlocks($startingBlocks, $iterables) {

        $posStart = array_pop($startingBlocks);
        $start = array_pop($startingBlocks);
        $end = str_replace('start', 'end', $start);
        $posEnd = strpos($this->view, $end, $posStart) + strlen($end);
        $keys = [];
        $parsedBlock = '';
        foreach($this->viewModel->getValue($iterables) as $iterable) {
            $keys = $this->getSubviewKeys($iterable);
            $this->populateSubView($iterable, $keys);
            $parsedBlock .= $this->parseTemplate(
                str_replace(
                    [$start, $end], '',
                    substr($this->view, $posStart, $posEnd - $posStart)
                )
            );
        }
        $this->cleanSubView($keys);
        $this->view = substr($this->view, 0, $posStart) .
            $parsedBlock . substr($this->view, $posEnd);

    }

    protected function parseTag($match) {
        $tag = 'parse' . ucfirst($match[1]);
        return $this->$tag($this->viewModel->clean($match[2]));
    }

    protected function parseValues($match) {
        return $value = $this->viewModel->get('values')->has($match[1]) ?
            $this->viewModel->clean($this->viewModel->getValue($match[1])) : $match[0];
    }

    protected function parseRequire($relativeUrl) {
        if (strpos($relativeUrl, DIRECTORY_SEPARATOR) === true) {
            throw new \Exception('Paths for templates disabled');
        }

        $subView = file_get_contents(
            $this->getTemplateDir() . $relativeUrl . '.html'
        );

        return $this->parseTemplate($subView);
    }




}