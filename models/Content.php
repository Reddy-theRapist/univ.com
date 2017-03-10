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
    public $subject;
    public $author;
    public $id;
    function __construct($id, $subject, $author, $content, $pd)
    {
        $this->id=$id;
        $this->content=$content;
        die($pd);
        $this->publication_date = DateTime::createFromFormat('Y-M-D G:i:s', $pd);
            $this->publication_date=$this->publication_date->format('D/M/Y G:i:s');
    }
}