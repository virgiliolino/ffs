<?php
namespace Ffs\MyApp\Model\FieldType;

use Ffs\Ffs\Model\FieldType\StringFieldType;

class CommentFieldType extends StringFieldType
{

    public function __construct($value = null)
    {
        parent::__construct($value);
        $this->setMaxLength(256);
        $this->setMinLength(4);
    }

    /**
     * @todo: completedly remove all tags.
     * replace every href|url in [href][url]
     * when displaying, change again href|url in <a href=....
     * @param $value
     * @return string
     */
    public function clean($value)
    {
        return strip_tags($value, '<a>');
    }
}