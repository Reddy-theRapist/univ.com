<?php

/**
 * Created by PhpStorm.
 * User: YoloSwaggerson
 * Date: 05.03.2017
 * Time: 16:18
 */
class model_Media extends Model_Base
{

    public $display_url;
    public $expanded_url;
    public $id;
    public $id_str;
    public $indices;
    public $media_url;
    public $media_url_https;
    public $sizes;
    public $source_status_id;
    public $source_status_id_str;
    public $type;
    public $url;

    public function fieldsTable()
    {
        return array(
            // здесь тоже лучше не забыть все описать
        );
    }
}