<?php
namespace Ffs\Ffs\Model;

use Ffs\Ffs\Model;

Abstract class WebModel extends AbstractModel {
    const VAL_POLICY_ALLOW = 'allow';
    const VAL_POLICY_DENY = 'deny';

    protected $tagsToSecure = [];

    protected $persistence = null;

    protected $denyAllowPolicy = self::VAL_POLICY_DENY;

    public function clean($value) {
        return $this->ParseText($value);
    }

    protected function preg_callback_url($matches)
    {
        $text = $matches[2];
        preg_match('@href="([^"]+)"@', $matches[1], $url);
        //return "{{link " . $url[1] . "|" . $text . "}}" ;
        return $url[1];
    }

    protected function ParseText($text){
        $text = preg_replace_callback('/<a\s(.+?)>(.+?)<\/a>/is',
            array( &$this, 'preg_callback_url'), $text);
        if ($this->denyAllowPolicy == self::VAL_POLICY_DENY) {
            $text = strip_tags($text);
        }
        return $text;
    }

}