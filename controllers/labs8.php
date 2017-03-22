<?php
/**
 * Created by PhpStorm.
 * User: YoloSwaggerson
 * Date: 04.03.2017
 * Time: 4:39
 */
require 'models/model_Table.php';
require 'twitter/TwitterManager.php';
require 'twitter_classes/TwitterUser.php';

class Controller_Labs8 Extends Controller_Base
{
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
        $demo = new Table("demo_table", "demo_db");

        $this->data = $demo->GetAllRows();
        $this->template->vars('data', $this->data);

        $this->twitterUser=new TwitterUser;

        if (isset($_GET["denied"]))
        {
            $this->twitter_auth_denied = true;
            $this->drop_session();
        }

        if (isset($_SESSION["access_token"]) && isset($_SESSION["access_token_secret"]))
        {
            $this->twitter_manager=new TwitterManager(Utils::CONSUMER_KEY, Utils::CONSUMER_SECRET, Utils::URL_CALLBACK);
            $this->twitter_manager->initOauth($_SESSION["access_token"], $_SESSION["access_token_secret"]);
            if (isset($_COOKIE["user_id"])&&isset($_COOKIE["screen_name"]))
            {
                $this->twitter_manager->_user_id=$_COOKIE["user_id"];
                $this->twitter_manager->_screen_name=$_COOKIE["screen_name"];
            }

            $this->getUser();
            $followers = json_decode($this->getUserFollowers($this->twitterUser),true);
            $this->twitterUser->followers = &$followers["users"];
            $followings= json_decode($this->getUserFollowed($this->twitterUser),true);
            $this->twitterUser->followed=$followings["users"];
            $this->twitterUser->advices[0]=json_decode($this->twitter_manager->GetUserByScreenName("YebaTu"),true);
        }
        else if (isset($_GET["oauth_verifier"])&&isset($_GET["oauth_token"]))
        {
            $this->twitter_manager = new TwitterManager(Utils::CONSUMER_KEY, Utils::CONSUMER_SECRET, Utils::URL_CALLBACK);
            $this->twitter_manager->access_token($_GET["oauth_token"], $_GET["oauth_verifier"]);
            header("Location: labs8");
        }

        $this->template->vars('twitterUser', $this->twitterUser);
        $this->template->vars('denied',$this->twitter_auth_denied);

        $this->template->view('index');
    }

    private function getUser()
    {
        $this->twitterUser->setAccessToken($_SESSION["access_token"]);
        $mismatchedVars = $this->twitterUser->CreateFromJSON(json_decode($this->twitter_manager->user_data()));
        if ($mismatchedVars)
            $this->template->vars('debug', $mismatchedVars);
    }

    private function getUserFollowers(&$theUser)
    {
        return $this->twitter_manager->getUserFollowers($theUser->screen_name);
    }
    private function getUserFollowed(&$theUser)
    {
        return $this->twitter_manager->getUsersFollowings($theUser->screen_name, -1, 100);
    }

    function db_connect(&$DBH)
    {
        try {$DBH=new PDO("mysql:host=localhost;dbname=demo_db","root","");}
        catch(PDOException $e) {die ('<br>error code: pizdec' . $e->getMessage());}
    }

    function drop_session()
    {
//        session_start();
        unset($_SESSION["denied"]);
        unset($_SESSION["oauth_token"]);
        unset($_SESSION["access_token"]);
        unset($_SESSION["access_token_secret"]);
        unset($_SESSION["oauth_token_secret"]);
        unset($_SESSION["twitter_auth_passed"]);
        unset($_GET["denied"]);
    }
}