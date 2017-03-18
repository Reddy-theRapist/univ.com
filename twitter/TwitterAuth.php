<?php

/**
 * Created by PhpStorm.
 * User: YoloSwaggerson
 * Date: 16.03.2017
 * Time: 12:49
 */


class TwitterAuth
{
    const URL_REQUEST_TOKEN	= 'https://api.twitter.com/oauth/request_token';
    const URL_AUTHORIZE		= 'https://api.twitter.com/oauth/authorize';
    const URL_ACCESS_TOKEN	= 'https://api.twitter.com/oauth/access_token';
    const URL_ACCOUNT_DATA	= 'https://api.twitter.com/1.1/users/show.json';

    // Секретные ключи и строка возврата
    private $_consumer_key = '';
    private $_consumer_secret = '';
    private $_url_callback = '';

    // Масив некоторых данных oauth
    private $_oauth = array();

    // Идентификатор Твиттер-пользователя
    private $_user_id = 0;
    private $_screen_name = '';

    // Текстовое сопровождение
    private $_text_support = false;

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
    public function request_token()
    {
        $this->_init_oauth();

        // ПОРЯДОК ПАРАМЕТРОВ ДОЛЖЕН БЫТЬ ИМЕННО ТАКОЙ!
        // Т.е. сперва oauth_callback -> oauth_consumer_key -> ... -> oauth_version.
        $oauth_base_text = "GET&";
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

        // Формируем GET-запрос
        $url = self::URL_REQUEST_TOKEN;
        $url .= '?oauth_callback='.urlencode($this->_url_callback);
        $url .= '&oauth_consumer_key='.$this->_consumer_key;
        $url .= '&oauth_nonce='.$this->_oauth['nonce'];
        $url .= '&oauth_signature='.urlencode($signature);
        $url .= '&oauth_signature_method=HMAC-SHA1';
        $url .= '&oauth_timestamp='.$this->_oauth['timestamp'];
        $url .= '&oauth_version=1.0';

        // Выполняем запрос
        $response = file_get_contents($url);

        //curl не парсит oauth_token в отдельный элемент массива, хотя в респонсе он есть
        #region hide
//        $ch = curl_init();
//        curl_setopt ($ch, CURLOPT_URL, $url);
//        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 20);
//        curl_setopt($ch, CURLOPT_NOBODY, 1);
//        curl_setopt($ch, CURLOPT_HEADER, 1);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

//        $response = curl_exec($ch);
//        curl_close($ch);
        #endregion

        // Парсим строку ответа
        parse_str($response, $result);
        // Запоминаем в сессию
        if ($result['oauth_token']!=''&&$result['oauth_token_secret']!='')
        {
            $_SESSION["bitch_please1"]= $_SESSION['oauth_token'] = $this->_oauth['token'] = $result['oauth_token'];
            $_SESSION['oauth_token_secret'] = $this->_oauth['token_secret'] = $result['oauth_token_secret'];
            $_SESSION['twitter_auth_passed']=1;
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

        // ПОРЯДОК ПАРАМЕТРОВ ДОЛЖЕН БЫТЬ ИМЕННО ТАКОЙ!
        // Т.е. сперва oauth_callback -> oauth_consumer_key -> ... -> oauth_version.
        $oauth_base_text = "GET&";
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
        $url = self::URL_ACCESS_TOKEN;
        $url .= '?oauth_nonce='.$this->_oauth['nonce'];
        $url .= '&oauth_signature_method=HMAC-SHA1';
        $url .= '&oauth_timestamp='.$this->_oauth['timestamp'];
        $url .= '&oauth_consumer_key='.$this->_consumer_key;
        $url .= '&oauth_token='.urlencode($this->_oauth['token']);
        $url .= '&oauth_verifier='.urlencode($this->_oauth['verifier']);
        $url .= '&oauth_signature='.urlencode($signature);
        $url .= '&oauth_version=1.0';


        // Выполняем запрос
        $response = file_get_contents($url);
        // Парсим результат запроса
        parse_str($response, $result);

        if (isset($result['oauth_token'])&&isset($result['oauth_token_secret']))
        {
        // Получаем идентификатор Твиттер-пользователя из результата запроса
            $this->_oauth['token'] = $result['oauth_token'];
            $this->_oauth['token_secret'] = $result['oauth_token_secret'];
            $this->_user_id = $result['user_id'];
            $this->_screen_name = $result['screen_name'];
        }

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