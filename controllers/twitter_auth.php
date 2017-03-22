<?php

session_start();

//$auth = array_key_exists('auth', $_GET);
//$denied = array_key_exists('denied', $_GET);

//if ($auth && !$denied)
//{
define ('TWITTER_CONSUMER_KEY',		'QFSoyAU8uiYUddFo2nVEWlpGU');
define ('TWITTER_CONSUMER_SECRET',	'IKyWN1PETjcOPD4aofrwZbSAfMrzbaklgUDkBah6eBBeQ9EnuI');
define ('TWITTER_URL_CALLBACK',		'http://univ.com/'.$_GET["callback"]);

include '../twitter/TwitterManager.php';

$TWAuth = new TwitterManager(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET, TWITTER_URL_CALLBACK);

$oauth_token = array_key_exists('oauth_token', $_GET) ? $_GET['oauth_token'] : false;
$oauth_verifier = array_key_exists('oauth_verifier', $_GET) ? $_GET['oauth_verifier'] : false;

if (!$oauth_token && !$oauth_verifier)
{
    $TWAuth->request_token();
    $TWAuth->authorize();
}
