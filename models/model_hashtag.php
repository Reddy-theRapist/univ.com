<?php

/**
 * Created by PhpStorm.
 * User: YoloSwaggerson
 * Date: 05.03.2017
 * Time: 14:28
 */
class model_Hashtag extends Model_Base
{

    public $indicies = array();
    public $text = "";

    public function fieldsTable()
    {
        return array(
            // здесь тоже лучше не забыть все описать
        );
    }
}
