<?php
/**
 * Created by PhpStorm.
 * User: YoloSwaggerson
 * Date: 04.03.2017
 * Time: 4:39
 */

class Controller_Labs8 Extends Controller_Base {

    // шаблон
    public $layouts = "skeleton";
    public $twitterUser;
    // главный экшен
    // если в url нет явно указанного экшена, то по дефолту вызывается index
    function index()
    {
        $this->twitterUser=new model_TwitterUser();
        if (!is_empty($_COOKIE['twitter']))
            $this->twitterUser;


        // здесь можно описать образщение к нужной модели
        // $obj = Model_Object();

        // передача некоторых данных в предсиавление
        // $data = [1, 2, 3];
        // $this->template->vars('data_name', $data');

        // вызов представления по имени
        $this->template->view('index');
    }

    function db_connect(&$DBH)
    {
        try {$DBH=new PDO("mysql:host=localhost;dbname=demo_db","root","");}
        catch(PDOException $e) {die ('<br>error code: pizdec' . $e->getMessage());}
    }

}