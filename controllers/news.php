<?php

/**
 * Created by PhpStorm.
 * User: YoloSwaggerson
 * Date: 04.03.2017
 * Time: 4:36
 */
class Controller_News Extends Controller_Base {

    // шаблон
    public $layouts = "skeleton";

    // главный экшен
    // если в url нет явно указанного экшена, то по дефолту вызывается index
    function index()
    {

        if (!isset($_COOKIE["testingCookies"]))
            setcookie("testingCookies","cookies are working", time()+3600,'/','www.univ.com',false,true);
        else $this->template->vars('cookies',$_COOKIE["testingCookies"]);
        if (!isset($_COOKIE["testingRawCookies"]))
            setrawcookie("testingRawCookies",rawurlencode("raw cookies are working"));
        else $this->template->vars('raw_cookies',$_COOKIE["testingRawCookies"]);

        $this->drop_session();
        $this->template->view('index');
    }

    function drop_session()
    {
        session_start();
        unset($_SESSION["denied"]);
        unset($_SESSION["access_token"]);
        unset($_SESSION["access_token_secret"]);
        unset($_SESSION["oauth_token"]);
        unset($_SESSION["oauth_token_secret"]);
        unset($_SESSION["twitter_auth_passed"]);
        unset($_GET["denied"]);

    }

}