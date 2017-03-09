<?php

/**
 * Created by PhpStorm.
 * User: YoloSwaggerson
 * Date: 05.03.2017
 * Time: 16:24
 */
class model_Mention extends Model_Base
{

    public $id;
    public $id_str;
    public $indicies;
    public $name;
    public $screen_name;

    public function fieldsTable()
    {
        return array(
            // здесь тоже лучше не забыть все описать
        );
    }
}