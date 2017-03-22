<?php

/**
 * Created by PhpStorm.
 * User: YoloSwaggerson
 * Date: 16.03.2017
 * Time: 12:49
 */
require_once "Utils.php";


class TwitterManager
{
    // Секретные ключи и строка возврата
    public $_consumer_key = '';
    public $_consumer_secret = '';
    public $_url_callback = '';

    // Масив некоторых данных oauth
    public $_oauth = array();

    // Идентификатор Твиттер-пользователя
    public $_user_id = 0;
    public $_screen_name = '';

    public function __construct($consumerkey, $consumersecret, $urlcallback)
    {
        $this->_consumer_key = $consumerkey;
        $this->_consumer_secret = $consumersecret;
        $this->_url_callback = $urlcallback;
    }

    public function initOauth($token, $token_secret)
    {
        $this->_oauth['token']=$token;
        $this->_oauth["token_secret"]=$token_secret;
    }

    public function request_token()
    {
        $this->_init_oauth();

        // ПОРЯДОК ПАРАМЕТРОВ ДОЛЖЕН БЫТЬ ИМЕННО ТАКОЙ!
        // Т.е. сперва oauth_callback -> oauth_consumer_key -> ... -> oauth_version.
        $oauth_base_text = "POST&";
        $oauth_base_text .= urlencode(Utils::URL_REQUEST_TOKEN)."&";
        $oauth_base_text .= urlencode("oauth_callback=".urlencode($this->_url_callback)."&");
        $oauth_base_text .= urlencode("oauth_consumer_key=".$this->_consumer_key."&");
        $oauth_base_text .= urlencode("oauth_nonce=".$this->_oauth['nonce']."&");
        $oauth_base_text .= urlencode("oauth_signature_method=HMAC-SHA1&");
        $oauth_base_text .= urlencode("oauth_timestamp=".$this->_oauth['timestamp']."&");
        $oauth_base_text .= urlencode("oauth_version=1.0");

        // Формируем ключ
        // На конце строки-ключа должен быть амперсанд & !!!
        $key = $this->_consumer_secret."&";

        // Формируем oauth_signature
        $signature = base64_encode(hash_hmac("sha1", $oauth_base_text, $key, true));

        $postfields = 'oauth_callback='.urlencode($this->_url_callback);
        $postfields .= '&oauth_consumer_key='.$this->_consumer_key;
        $postfields.= '&oauth_nonce='.$this->_oauth['nonce'];
        $postfields.= '&oauth_signature='.urlencode($signature);
        $postfields.= '&oauth_signature_method=HMAC-SHA1';
        $postfields.= '&oauth_timestamp='.$this->_oauth['timestamp'];
        $postfields.= '&oauth_version=1.0';

        #endregion
        // Парсим строку ответа
        $response=Utils::CURL_SEND_REQUEST(Utils::URL_REQUEST_TOKEN,"post",$postfields);
//        die($response);
        parse_str($response, $result);
        preg_match('/oauth_token=[^&]*/', $response, $matches);
        $result["oauth_token"] = str_replace("oauth_token=", "", $matches[0]);

        // Запоминаем в сессию
        if ($result['oauth_token']!=''&&$result['oauth_token_secret']!='')
        {
            $_SESSION['oauth_token'] = $this->_oauth['token'] = $result['oauth_token'];
            $_SESSION['oauth_token_secret'] = $this->_oauth['token_secret'] = $result['oauth_token_secret'];
        }
    }

    public function authorize()
    {
        // Формируем GET-запрос
        $url = Utils::URL_AUTHORIZE;
        $url .= '?oauth_token='.$this->_oauth['token'];

        header('Location: '.$url.'');
    }


    public function access_token($token, $verifier)
    {
        $this->_init_oauth();

        // Токен из ГЕТ-запроса
        $this->_oauth['token'] = $token;

        // Вспоминаем oauth_token_secret из сессии (см. функцию request_token)
        $this->_oauth['token_secret'] = $_SESSION['oauth_token_secret'];

        // Токен из ГЕТ-запроса
        $this->_oauth['verifier'] = $verifier;

        // ПОРЯДОК ПАРАМЕТРОВ ДЛЯ СИГНАТУРЫ ДОЛЖЕН БЫТЬ ИМЕННО ТАКОЙ!
        //также их будет собирать twitter и проверять сигнатуру (но это не точно (с))
        $oauth_base_text = "POST&";
        $oauth_base_text .= urlencode(Utils::URL_ACCESS_TOKEN)."&";
        $oauth_base_text .= urlencode("oauth_consumer_key=".$this->_consumer_key."&");
        $oauth_base_text .= urlencode("oauth_nonce=".$this->_oauth['nonce']."&");
        $oauth_base_text .= urlencode("oauth_signature_method=HMAC-SHA1&");
        $oauth_base_text .= urlencode("oauth_token=".$this->_oauth['token']."&");
        $oauth_base_text .= urlencode("oauth_timestamp=".$this->_oauth['timestamp']."&");
        $oauth_base_text .= urlencode("oauth_verifier=".$this->_oauth['verifier']."&");
        $oauth_base_text .= urlencode("oauth_version=1.0");

        // Формируем ключ (Consumer secret + '&' + oauth_token_secret)
        $key = $this->_consumer_secret."&".$this->_oauth['token_secret'];

        // Формируем auth_signature
        $signature = base64_encode(hash_hmac("sha1", $oauth_base_text, $key, true));

        // Формируем GET-запрос
        $postfields = 'oauth_nonce='.$this->_oauth['nonce'];
        $postfields .= '&oauth_signature_method=HMAC-SHA1';
        $postfields .= '&oauth_timestamp='.$this->_oauth['timestamp'];
        $postfields .= '&oauth_consumer_key='.$this->_consumer_key;
        $postfields .= '&oauth_token='.urlencode($this->_oauth['token']);
        $postfields .= '&oauth_verifier='.urlencode($this->_oauth['verifier']);
        $postfields .= '&oauth_signature='.urlencode($signature);
        $postfields .= '&oauth_version=1.0';


        $response=Utils::CURL_SEND_REQUEST(Utils::URL_ACCESS_TOKEN,"post",$postfields);

        parse_str($response, $result);
        preg_match('/oauth_token=[^&]*/', $response, $matches);
        $result["oauth_token"] = str_replace("oauth_token=", "", $matches[0]);

//        return (print_r($result));
//        die($response);

        if (isset($result['oauth_token'])&&isset($result['screen_name']))
        {
        // Получаем идентификатор Твиттер-пользователя из результата запроса
            $_SESSION["access_token"]=$this->_oauth['token'] = $result['oauth_token'];
            $_SESSION["access_token_secret"]=$this->_oauth['token_secret'] = $result['oauth_token_secret'];
            $this->_user_id = $result['user_id'];
            $this->_screen_name = $result['screen_name'];

            setcookie("screen_name",$this->_screen_name);//,time()+3600,'/','www.univ.com');//,false,true);
            setcookie("user_id",$this->_user_id);//,time()+3600,'/','www.univ.com');//,false,true);
        }
//        else die ("die motherfucker die motherfucker die");

    }

    public function user_data()
    {
        $this->_init_oauth();

        $oauth_base_text = "GET&";
        $oauth_base_text .= urlencode(Utils::URL_ACCOUNT_DATA).'&';
        $oauth_base_text .= urlencode('oauth_consumer_key='.$this->_consumer_key.'&');
        $oauth_base_text .= urlencode('oauth_nonce='.$this->_oauth['nonce'].'&');
        $oauth_base_text .= urlencode('oauth_signature_method=HMAC-SHA1&');
        $oauth_base_text .= urlencode('oauth_timestamp='.$this->_oauth['timestamp']."&");
        $oauth_base_text .= urlencode('oauth_token='.$this->_oauth['token']."&");
        $oauth_base_text .= urlencode('oauth_version=1.0&');
        $oauth_base_text .= urlencode('screen_name=' . $this->_screen_name);

        $key = $this->_consumer_secret . '&' . $this->_oauth['token_secret'];
        $signature = base64_encode(hash_hmac("sha1", $oauth_base_text, $key, true));

        // Формируем GET-запрос
        $url = Utils::URL_ACCOUNT_DATA;
        $url .= '?oauth_consumer_key=' . $this->_consumer_key;
        $url .= '&oauth_nonce=' . $this->_oauth['nonce'];
        $url .= '&oauth_signature=' . urlencode($signature);
        $url .= '&oauth_signature_method=HMAC-SHA1';
        $url .= '&oauth_timestamp=' . $this->_oauth['timestamp'];
        $url .= '&oauth_token=' . urlencode($this->_oauth['token']);
        $url .= '&oauth_version=1.0';
        $url .= '&screen_name=' . $this->_screen_name;

        // Выполняем запрос
//        $response = file_get_contents($url);
       $response = Utils::CURL_SEND_REQUEST($url,"get",null,false); //doesn't work!!!
        return $response;
    }

    public function getUserFollowers($user_screenName)
    {
        $this->_init_oauth();

        $oauth_base_text = "GET&";
        $oauth_base_text .= urlencode(Utils::URL_FOLLOWERS_LIST).'&';
        $oauth_base_text .= urlencode('oauth_consumer_key='.$this->_consumer_key.'&');
        $oauth_base_text .= urlencode('oauth_nonce='.$this->_oauth['nonce'].'&');
        $oauth_base_text .= urlencode('oauth_signature_method=HMAC-SHA1&');
        $oauth_base_text .= urlencode('oauth_timestamp='.$this->_oauth['timestamp']."&");
        $oauth_base_text .= urlencode('oauth_token='.$this->_oauth['token']."&");
        $oauth_base_text .= urlencode('oauth_version=1.0&');
        $oauth_base_text .= urlencode('screen_name=' . $user_screenName);

        $key = $this->_consumer_secret . '&' . $this->_oauth['token_secret'];
        $signature = base64_encode(hash_hmac("sha1", $oauth_base_text, $key, true));

        // Формируем GET-запрос
        $url = Utils::URL_FOLLOWERS_LIST;
        $url .= '?oauth_consumer_key=' . $this->_consumer_key;
        $url .= '&oauth_nonce=' . $this->_oauth['nonce'];
        $url .= '&oauth_signature=' . urlencode($signature);
        $url .= '&oauth_signature_method=HMAC-SHA1';
        $url .= '&oauth_timestamp=' . $this->_oauth['timestamp'];
        $url .= '&oauth_token=' . urlencode($this->_oauth['token']);
        $url .= '&oauth_version=1.0';
        $url .= '&screen_name=' . $user_screenName;


        return Utils::CURL_SEND_REQUEST($url,"get",null,false);
//        return file_get_contents($url);
    }

    //рефакторинг? ООП? кто все эти люди
    //дублирование одного и того же кода - вот наш девиз
    public function banUsersFollower($poorGuyID){
        $this->_init_oauth();

        $oauth_base_text = "POST&";
        $oauth_base_text .= urlencode(Utils::URL_BLOCKS_CREATE).'&';
        $oauth_base_text .= urlencode('oauth_consumer_key='.$this->_consumer_key.'&');
        $oauth_base_text .= urlencode('oauth_nonce='.$this->_oauth['nonce'].'&');
        $oauth_base_text .= urlencode('oauth_signature_method=HMAC-SHA1&');
        $oauth_base_text .= urlencode('oauth_timestamp='.$this->_oauth['timestamp']."&");
        $oauth_base_text .= urlencode('oauth_token='.$this->_oauth['token']."&");
        $oauth_base_text .= urlencode('oauth_version=1.0&');
        $oauth_base_text .= urlencode('user_id=' . $poorGuyID);

        $key = $this->_consumer_secret . '&' . $this->_oauth['token_secret'];
        $signature = base64_encode(hash_hmac("sha1", $oauth_base_text, $key, true));

        $postfields = 'oauth_consumer_key=' . $this->_consumer_key;
        $postfields .= '&oauth_nonce=' . $this->_oauth['nonce'];
        $postfields .= '&oauth_signature=' . urlencode($signature);
        $postfields .= '&oauth_signature_method=HMAC-SHA1';
        $postfields .= '&oauth_timestamp=' . $this->_oauth['timestamp'];
        $postfields .= '&oauth_token=' . urlencode($this->_oauth['token']);
        $postfields .= '&oauth_version=1.0';
        $postfields .= '&user_id=' . $poorGuyID;


        return Utils::CURL_SEND_REQUEST(Utils::URL_BLOCKS_CREATE,"post",$postfields,true,true);
    }

    public function getUsersFollowings($user_screenName, $cursor, $count)
    {
        $this->_init_oauth();

        $oauth_base_text = "GET&";
        $oauth_base_text .= urlencode(Utils::URL_FOLLOWED_LIST).'&';
        $oauth_base_text .= urlencode('count='.$count.'&');
        $oauth_base_text .= urlencode('cursor='.$cursor.'&');
        $oauth_base_text .= urlencode('oauth_consumer_key='.$this->_consumer_key.'&');
        $oauth_base_text .= urlencode('oauth_nonce='.$this->_oauth['nonce'].'&');
        $oauth_base_text .= urlencode('oauth_signature_method=HMAC-SHA1&');
        $oauth_base_text .= urlencode('oauth_timestamp='.$this->_oauth['timestamp']."&");
        $oauth_base_text .= urlencode('oauth_token='.$this->_oauth['token']."&");
        $oauth_base_text .= urlencode('oauth_version=1.0&');
        $oauth_base_text .= urlencode('screen_name=' . $user_screenName);

        $key = $this->_consumer_secret . '&' . $this->_oauth['token_secret'];
        $signature = base64_encode(hash_hmac("sha1", $oauth_base_text, $key, true));

        // Формируем GET-запрос
        $url = Utils::URL_FOLLOWED_LIST;
        $url .= '?oauth_consumer_key=' . $this->_consumer_key;
        $url .= '&oauth_nonce=' . $this->_oauth['nonce'];
        $url .= '&oauth_signature=' . urlencode($signature);
        $url .= '&oauth_signature_method=HMAC-SHA1';
        $url .= '&oauth_timestamp=' . $this->_oauth['timestamp'];
        $url .= '&oauth_token=' . urlencode($this->_oauth['token']);
        $url .= '&oauth_version=1.0';
        $url .= '&count=' . urlencode($count);
        $url .= '&cursor=' . urlencode($cursor);
        $url .= '&screen_name=' . $user_screenName;

//        die($url);
        return Utils::CURL_SEND_REQUEST($url,"get",null,false);
    }

    public function SubscribeUser($poorGuyID)
    {
        $this->_init_oauth();

        $oauth_base_text = "POST&";
        $oauth_base_text .= urlencode(Utils::URL_FRIENDSHIP_CREATE).'&';
        $oauth_base_text .= urlencode('oauth_consumer_key='.$this->_consumer_key.'&');
        $oauth_base_text .= urlencode('oauth_nonce='.$this->_oauth['nonce'].'&');
        $oauth_base_text .= urlencode('oauth_signature_method=HMAC-SHA1&');
        $oauth_base_text .= urlencode('oauth_timestamp='.$this->_oauth['timestamp']."&");
        $oauth_base_text .= urlencode('oauth_token='.$this->_oauth['token']."&");
        $oauth_base_text .= urlencode('oauth_version=1.0&');
        $oauth_base_text .= urlencode('user_id=' . $poorGuyID);

        $key = $this->_consumer_secret . '&' . $this->_oauth['token_secret'];
        $signature = base64_encode(hash_hmac("sha1", $oauth_base_text, $key, true));

        $postfields = 'oauth_consumer_key=' . $this->_consumer_key;
        $postfields .= '&oauth_nonce=' . $this->_oauth['nonce'];
        $postfields .= '&oauth_signature=' . urlencode($signature);
        $postfields .= '&oauth_signature_method=HMAC-SHA1';
        $postfields .= '&oauth_timestamp=' . $this->_oauth['timestamp'];
        $postfields .= '&oauth_token=' . urlencode($this->_oauth['token']);
        $postfields .= '&oauth_version=1.0';
        $postfields .= '&user_id=' . $poorGuyID;

        return Utils::CURL_SEND_REQUEST(Utils::URL_FRIENDSHIP_CREATE,"post",$postfields,true,true);
    }

    public function GetUserByScreenName($screen_name)
    {
        $data = array('grant_type' => 'client_credentials');
        $opts = array('http' =>
            array(
                'method'  => 'POST',
                'header'  =>"Content-type: application/x-www-form-urlencoded;charset=UTF-8\r\n" .
                    'Authorization: Basic ' . base64_encode(Utils::CONSUMER_KEY . ':' . Utils::CONSUMER_SECRET) . "\r\n",
                'content' => http_build_query($data)
            )
        );
        $context  = stream_context_create($opts);

        $response = file_get_contents(Utils::URL_OAUTH2_TOKEN, false, $context);
        $result = json_decode($response, true);

        $opts = array('http' =>
            array(
                'method'  => 'GET',
                'header'  =>"Content-type: application/x-www-form-urlencoded;charset=UTF-8\r\n" .
                    'Authorization: Bearer ' . $result['access_token'] . "\r\n",
            )
        );

        $url = Utils::URL_ACCOUNT_DATA . '?screen_name=' . $screen_name;

        return file_get_contents($url, false, stream_context_create($opts));;
    }

    private function _init_oauth()
    {
        $this->_oauth['nonce'] = md5(uniqid(rand(), true)); // Формируем oauth_nonce
        $this->_oauth['timestamp'] = time(); // Получаем текущее время в секундах
    }
}