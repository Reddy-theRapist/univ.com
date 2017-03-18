<?php

/**
 * Created by PhpStorm.
 * User: YoloSwaggerson
 * Date: 05.03.2017
 * Time: 16:23
 */
class model_URL extends Model_Base
{
    public $display_url;
    public $expanded_url;
    public $indices;
    public $url;

    public function fieldsTable()
    {
        return array(
            // здесь тоже лучше не забыть все описать
        );
    }
}