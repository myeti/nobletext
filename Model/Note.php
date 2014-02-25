<?php

namespace My\Model;

class Note
{

    use Note\Provider;

    /** @var string */
    public $id;

    /** @var string */
    public $title;

    /** @var string */
    public $content;

    /** @var string */
    public $date;

} 