<?php

session_start();

//$auth = array_key_exists('auth', $_GET);
//$denied = array_key_exists('denied', $_GET);

//if ($auth && !$denied)
//{
define ('TWITTER_CONSUMER_KEY',		'QFSoyAU8uiYUddFo2nVEWlpGU');
define ('TWITTER_CONSUMER_SECRET',	'IKyWN1PETjcOPD4aofrwZbSAfMrzbaklgUDkBah6eBBeQ9EnuI');
define ('TWITTER_URL_CALLBACK',		'http://univ.com/'.$_GET["callback"]);

include '../twitter/TwitterAuth.php';

$TWAuth = new TwitterAuth(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET, TWITTER_URL_CALLBACK);

$oauth_token = array_key_exists('oauth_token', $_GET) ? $_GET['oauth_token'] : false;
$oauth_verifier = array_key_exists('oauth_verifier', $_GET) ? $_GET['oauth_verifier'] : false;

if (!$oauth_token && !$oauth_verifier)
{
    $TWAuth->request_token();
    $TWAuth->authorize();
}
else
{
    // access_token и user_id
    $TWAuth->access_token($oauth_token, $oauth_verifier);

    // JSON-версия
    $user_data = $TWAuth->user_data();
    $user_data = json_decode($user_data);

    echo '<pre>User data<br>';
    print_r($user_data);
    echo '</pre>';
    die();

    // XML-версия
    // $user_data = $TWAuth->user_data('xml');
}
//}
//else
//{
//
//	echo '<p><a href="twitter_auth.php?auth=1">Начать авторизацию через Твиттер</a></p>';
//	echo '<p>Скачать архив: <a href="TwitterAuth.rar">TwitterAuth.rar</a></p>';
//}
//