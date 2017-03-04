<?php

/**
 * Created by PhpStorm.
 * User: YoloSwaggerson
 * Date: 04.03.2017
 * Time: 5:49
 */
class model_TwitterUser Extends Model_Base
{

    public $id;
    public $name;

    // далее описать все остальные поля сущности

    public function fieldsTable()
    {
        return array(
            'id' => 'Id',
            // здесь тоже лучше не забыть все описать
        );
    }
}
?>