<?php

/**
 * Created by PhpStorm.
 * User: YoloSwaggerson
 * Date: 21.03.2017
 * Time: 3:02
 */
class twitter_model
{
    public $initialized = false;

    public function CreateFromJSON($data)
    {
        if (count($data)===0)
            return null;

        $mismatched =  array();

        foreach ($data as $key=>$datum) {
            if (property_exists($this,$key))
                $this->$key=$datum;
            else $mismatched[$key]=$datum;
        }

        $this->initialized=true;

        if (count($mismatched)>0)
            return "mismatch between json data and user class definition:".print_r($mismatched, true).'<br>';
        return false;
    }
}