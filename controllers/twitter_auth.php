<?php
/**
 * Created by PhpStorm.
 * User: YoloSwaggerson
 * Date: 11.03.2017
 * Time: 0:51
 */
session_start();
$oauth_nonce = sha1(random_bytes(32));

$post_data=array();
$post_data["Accept"]="*/*";
$post_data["Connection"]="close";
$post_data["User-Agent"]="OAuth gem v0.4.4";
$post_data["Content-Type"]="";
$post_data["Accept"]="application/x-www-form-urlencoded";
$post_data["Authorization"]="OAuth oauth_consumer_key=\"QFSoyAU8uiYUddFo2nVEWlpGU\", oauth_nonce=\"$oauth_nonce\",oauth_signature=\"tnnArxj06cWHq44gCs1OSKk%2FjLY%3D\",oauth_signature_method=\"HMAC-SHA1\",oauth_timestamp=\"".DateTime::getTimestamp()."\",oauth_token=\"370773112-GmHxMAgYyLbNEtIKZeRNFsMKPR9EyMZeS9weJAEb\",oauth_version=\"1.0\"";
$post_data["Content-length"]=76;
$post_data["Host"]="https://api.twitter.com";
$post_data["status"]="Hello%20Ladies%20%2b%20Gentlemen%2c%20a%20signed%20OAuth%20request%21";

http_post_data("https://api.twitter.com/1/statuses/update.json",$post_data);


if ($ch=curl_init("api.twitter.com"))
{
    curl_setopt($ch,CURLOPT_HEADEROPT,CURLOPT_HTTPHEADER);
//    curl_setopt($ch,CURLINFO_CONTENT_TYPE,"application/x-www-form-urlencoded");
    curl_setopt($ch,CURLOPT_HEADER, 1);
    curl_setopt($ch,CURLOPT_POST,"POST /1/statuses/update.json?include_entities=true HTTP/1.1
            Accept: */*
            Connection: close
            User-Agent: OAuth gem v0.4.4
            Content-Type: application/x-www-form-urlencoded
            Authorization: OAuth oauth_consumer_key=\"QFSoyAU8uiYUddFo2nVEWlpGU\", oauth_nonce=\"$oauth_nonce\",oauth_signature=\"tnnArxj06cWHq44gCs1OSKk%2FjLY%3D\",oauth_signature_method=\"HMAC-SHA1\",oauth_timestamp=\"".DateTime::getTimestamp()."\",oauth_token=\"370773112-GmHxMAgYyLbNEtIKZeRNFsMKPR9EyMZeS9weJAEb\",oauth_version=\"1.0\"
            Content-Length: 76
            Host: api.twitter.com
            
            status=Hello%20Ladies%20%2b%20Gentlemen%2c%20a%20signed%20OAuth%20request%21");
    curl_exec($ch);
    curl_close($ch);
}
else die("die mothafucka die mothafucka di-ie");