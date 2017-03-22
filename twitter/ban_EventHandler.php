<?php

require_once 'TwitterManager.php';
session_start();

if (isset($_SESSION["access_token"]) && isset($_SESSION["access_token_secret"]))
{
    $twitterManager=new TwitterManager(Utils::CONSUMER_KEY, Utils::CONSUMER_SECRET, Utils::URL_CALLBACK);
    $twitterManager->initOauth($_SESSION["access_token"], $_SESSION["access_token_secret"]);
    if (isset($_COOKIE["user_id"])&&isset($_COOKIE["screen_name"]))
    {
        $twitterManager->_user_id=$_COOKIE["user_id"];
        $twitterManager->_screen_name=$_COOKIE["screen_name"];
    }
    $op_status=$twitterManager->banUsersFollower(urldecode($_POST["fagID"]));
    //TODO проверка header'а в $op_status header'е
    echo json_encode(array("op_status"=>1,"debug"=>$op_status));
}
