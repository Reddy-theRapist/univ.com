<?php

/**
 * Created by PhpStorm.
 * User: YoloSwaggerson
 * Date: 16.03.2017
 * Time: 12:49
 */


class TwitterManager
{
    const URL_REQUEST_TOKEN	= 'https://api.twitter.com/oauth/request_token';
    const URL_AUTHORIZE		= 'https://api.twitter.com/oauth/authorize';
    const URL_ACCESS_TOKEN	= 'https://api.twitter.com/oauth/access_token';
    const URL_ACCOUNT_DATA	= 'https://api.twitter.com/1.1/users/show.json';

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

    /**
     * Первый этап
     *
     */



    private FUNCTION CURL_SEND_REQUEST($target_url, $request_type, $postfields=null, $include_header=true)
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

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error)
            return $error;
        return $response;
    }

    public function request_token()
    {
        $this->_init_oauth();

        // ПОРЯДОК ПАРАМЕТРОВ ДОЛЖЕН БЫТЬ ИМЕННО ТАКОЙ!
        // Т.е. сперва oauth_callback -> oauth_consumer_key -> ... -> oauth_version.
        $oauth_base_text = "POST&";
        $oauth_base_text .= urlencode(self::URL_REQUEST_TOKEN)."&";
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
        $response=$this->CURL_SEND_REQUEST(self::URL_REQUEST_TOKEN,"post",$postfields);

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


    /**
     * Второй этап
     */
    public function authorize()
    {
        // Формируем GET-запрос
        $url = self::URL_AUTHORIZE;
        $url .= '?oauth_token='.$this->_oauth['token'];

        header('Location: '.$url.'');
    }


    /**
     * Третий этап
     */
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
        $oauth_base_text .= urlencode(self::URL_ACCESS_TOKEN)."&";
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


        $response=$this->CURL_SEND_REQUEST(self::URL_ACCESS_TOKEN,"post",$postfields);

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


    /**
     * Четвертый этап. Получение данных пользователя
     *
     */
    public function user_data()
    {
        $this->_init_oauth();

        $oauth_base_text = "GET&";
        $oauth_base_text .= urlencode(self::URL_ACCOUNT_DATA).'&';
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
        $url = self::URL_ACCOUNT_DATA;
        $url .= '?oauth_consumer_key=' . $this->_consumer_key;
        $url .= '&oauth_nonce=' . $this->_oauth['nonce'];
        $url .= '&oauth_signature=' . urlencode($signature);
        $url .= '&oauth_signature_method=HMAC-SHA1';
        $url .= '&oauth_timestamp=' . $this->_oauth['timestamp'];
        $url .= '&oauth_token=' . urlencode($this->_oauth['token']);
        $url .= '&oauth_version=1.0';
        $url .= '&screen_name=' . $this->_screen_name;

        // Выполняем запрос
        $response = file_get_contents($url);
//            $this->CURL_SEND_REQUEST($url,"get");
//        die(json_decode($response));
        // Возвращаем результат
        return $response;
    }


    /**
     * Функция формирует oauth_nonce и oauth_timestamp
     *
     */
    private function _init_oauth()
    {
        $this->_oauth['nonce'] = md5(uniqid(rand(), true)); // Формируем oauth_nonce
        $this->_oauth['timestamp'] = time(); // Получаем текущее время в секундах
    }
}