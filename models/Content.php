<?php

/**
 * Created by PhpStorm.
 * User: YoloSwaggerson
 * Date: 10.03.2017
 * Time: 1:16
 */
class Content
{
    public $publication_date;
    public $content;
    public $id;
    function __construct(&$id, &$content, &$pd)
    {$this->id=$id; $this->content=$content; $this->publication_date=$pd;}
}