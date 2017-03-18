<?php

/**
 * Created by PhpStorm.
 * User: YoloSwaggerson
 * Date: 04.03.2017
 * Time: 4:38
 */
class Controller_Contacts Extends Controller_Base {

    // шаблон
    public $layouts = "skeleton";

    // главный экшен
    // если в url нет явно указанного экшена, то по дефолту вызывается index
    function index() {

        // здесь можно описать образщение к нужной модели
        // $obj = Model_Object();

        // передача некоторых данных в предсиавление
        // $data = [1, 2, 3];
        // $this->template->vars('data_name', $data');

        // вызов представления по имени
        $this->template->view('index');
    }

}