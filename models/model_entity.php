<?php

/**
 * Created by PhpStorm.
 * User: YoloSwaggerson
 * Date: 05.03.2017
 * Time: 14:24
 */
class model_Entity extends Model_Base
{

    public $hashtags;
    public $media=array();
    public $urls;
    public $user_mentions;


    public function fieldsTable()
    {
        return array(
            'id' => 'Id',
            // здесь тоже лучше не забыть все описать
        );
    }
}