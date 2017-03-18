<?php
/**
 * Created by PhpStorm.
 * User: YoloSwaggerson
 * Date: 04.03.2017
 * Time: 4:39
 */
require 'models/model_Table.php';
require 'twitter/TwitterAuth.php';
require 'twitter_classes/TwitterUser.php';

class Controller_Labs8 Extends Controller_Base
{


    const CONSUMER_KEY='QFSoyAU8uiYUddFo2nVEWlpGU';
    const CONSUMER_SECRET='IKyWN1PETjcOPD4aofrwZbSAfMrzbaklgUDkBah6eBBeQ9EnuI';
    const URL_CALLBACK='http://univ.com/labs8';

    // шаблон
    public $layouts = "skeleton";
    public $twitterUser;
    private $twitter_manager;
    public $data;
    // главный экшен
    // если в url нет явно указанного экшена, то по дефолту вызывается index
    private $twitter_auth_denied=false;

    function index()
    {
        session_start();
        $demo = new Table("demo_table","demo_db");

        $this->data=$demo->GetAllRows();
        $this->template->vars('data',$this->data);

        //$_GET["oauth_token"] === $_SESSION["oauth_token"]!!!

        if (isset($_SESSION["twitter_auth_passed"]))
        {
            if (isset($_GET["denied"]))
            {
                $this->twitter_auth_denied = true;
                $this->drop_session();
            }
            else if ($_SESSION["twitter_auth_passed"]===1)
            {
                if (isset($_GET["oauth_verifier"]))
                    $_SESSION["oauth_verifier"]=$_GET["oauth_verifier"];
                //есть $_GET["oauth_token"] и $_GET["oauth_verifier"]
                $this->twitter_manager = new TwitterAuth(self::CONSUMER_KEY, self::CONSUMER_SECRET, self::URL_CALLBACK);
                $this->twitter_manager->access_token($_SESSION["oauth_token"], $_SESSION["oauth_verifier"]);

                $this->twitterUser = new TwitterUser();
                $mismatchedVars = $this->twitterUser->CreateFromJSON(json_decode($this->twitter_manager->user_data()));

//                $mismatchedVars = $this->twitterUser->CreateFromJSON(json_decode($this->twitter_manager->user_data()));
//
//
//
//                echo '<p>'.$this->twitterUser.'</p><hr/>'; // this is FINE
                if ($mismatchedVars)
                    $this->template->vars('debug', $mismatchedVars);
                $this->template->vars('twitter_user', $this->twitterUser);
                $this->template->vars('twitter_manager', $this->twitter_manager);
//                $this->template->vars('debug2', $_SESSION["bitch_please1"] . " x#########x " . $_GET["oauth_token"]);
            }
        }

        $this->template->vars('denied',$this->twitter_auth_denied);

        // вызов представления по имени
        $this->template->view('index');
    }


    function db_connect(&$DBH)
    {
        try {$DBH=new PDO("mysql:host=localhost;dbname=demo_db","root","");}
        catch(PDOException $e) {die ('<br>error code: pizdec' . $e->getMessage());}
    }

    function drop_session()
    {
        session_start();
        unset($_SESSION["denied"]);
        unset($_SESSION["oauth_token"]);
        unset($_SESSION["oauth_token_secret"]);
        unset($_SESSION["twitter_auth_passed"]);
        unset($_GET["denied"]);

    }
}