<?php

/**
 * Created by PhpStorm.
 * User: YoloSwaggerson
 * Date: 05.03.2017
 * Time: 16:22
 */
class model_Sizes extends Model_Base
{

    public $thumb;
    public $large;
    public $medium;
    public $small;

    public function fieldsTable()
    {
        return array(
            // здесь тоже лучше не забыть все описать
        );
    }
}