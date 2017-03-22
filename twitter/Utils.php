<?php

/**
 * Created by PhpStorm.
 * User: YoloSwaggerson
 * Date: 21.03.2017
 * Time: 14:24
 */
class Utils
{
    public const URL_REQUEST_TOKEN	= 'https://api.twitter.com/oauth/request_token';
    public const URL_AUTHORIZE		= 'https://api.twitter.com/oauth/authorize';
    public const URL_ACCESS_TOKEN	= 'https://api.twitter.com/oauth/access_token';
    public const URL_ACCOUNT_DATA	= 'https://api.twitter.com/1.1/users/show.json';
    public const URL_FOLLOWERS_LIST='https://api.twitter.com/1.1/followers/list.json';
    public const URL_BLOCKS_CREATE='https://api.twitter.com/1.1/blocks/create.json';
    public const URL_FOLLOWED_LIST='https://api.twitter.com/1.1/friends/list.json';
    //эквивалентно подписке
    public const  URL_FRIENDSHIP_CREATE='https://api.twitter.com/1.1/friendships/create.json';
    public const  URL_OAUTH2_TOKEN='https://api.twitter.com/oauth2/token';

    public const CONSUMER_KEY='QFSoyAU8uiYUddFo2nVEWlpGU';
    public const CONSUMER_SECRET='IKyWN1PETjcOPD4aofrwZbSAfMrzbaklgUDkBah6eBBeQ9EnuI';
    public const URL_CALLBACK='http://univ.com/labs8';



    public static function CURL_SEND_REQUEST($target_url, $request_type, $postfields=null, $include_header=true, $include_body=true)
    {
        $ch= curl_init();

        switch (strtolower($request_type))
        {
            case "post":
                curl_setopt($ch,CURLOPT_POST,true);
                curl_setopt($ch,CURLOPT_POSTFIELDS,$postfields);
                break;
            case "get":
                break;
            case "put":
                //это вообще хуй знает зачем. не put request, а загрузка файла
//                curl_setopt($ch,CURLOPT_PUT,true);
                break;
            case "delete":
                break;
        }


        curl_setopt($ch, CURLOPT_URL, $target_url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch,CURLOPT_HEADER,$include_header);
        curl_setopt($ch,CURLOPT_NOBODY,!$include_body);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error)
            return $error;
        return $response;
    }
}