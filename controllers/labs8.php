<?php
/**
 * Created by PhpStorm.
 * User: YoloSwaggerson
 * Date: 04.03.2017
 * Time: 4:39
 */
require 'models/model_Table.php';

class Controller_Labs8 Extends Controller_Base {

    // шаблон
    public $layouts = "skeleton";
    public $twitterUser;
    public $data;
    // главный экшен
    // если в url нет явно указанного экшена, то по дефолту вызывается index
    function index()
    {
        $demo = new Table("demo_table","demo_db");

        $this->data=$demo->GetAllRows();
        $this->template->vars('data',$this->data);

//        foreach ($data  as $key=>$value)
//            $this->template->vars($key,$value);

//        $this->twitterUser=new model_TwitterUser();
//        if (!is_empty($_COOKIE['twitter']))
//            $this->twitterUser;


        // вызов представления по имени
        $this->template->view('index');
    }

    function db_connect(&$DBH)
    {
        try {$DBH=new PDO("mysql:host=localhost;dbname=demo_db","root","");}
        catch(PDOException $e) {die ('<br>error code: pizdec' . $e->getMessage());}
    }

}