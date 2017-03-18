<?php

class Utils {
    public static function redirect($uri = '') {
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: ".$uri, TRUE, 302);
        exit;
    }
}

class OAuthTwitter {

    const CONSUMER_KEY = 'QFSoyAU8uiYUddFo2nVEWlpGU';
    const CONSUMER_SECRET = 'IKyWN1PETjcOPD4aofrwZbSAfMrzbaklgUDkBah6eBBeQ9EnuI';
    const URL_CALLBACK = 'http://univ.com/twitter_resources/twitter.php'; //URL, на который произойдет перенаправление после авторизации

    const URL_GET_TOKEN = 'https://api.twitter.com/oauth2/token';
    const URL_REQUEST_TOKEN = 'https://api.twitter.com/oauth/request_token';
    const URL_AUTHORIZE = 'https://api.twitter.com/oauth/authorize';
    const URL_ACCESS_TOKEN = 'https://api.twitter.com/oauth/access_token';
    const URL_USER_DATA = 'https://api.twitter.com/1.1/users/show.json';

    private static $token;
    public static $userId;
    public static $userData;

    public static function goToAuth() {
        $oauth_nonce = md5(uniqid(rand(), true));
        $oauth_timestamp = time();

        $oauth_base_text = "GET&" .
            urlencode(self::URL_REQUEST_TOKEN) . "&" .
            urlencode(
                "oauth_callback=" . urlencode(self::URL_CALLBACK) . "&" .
                "oauth_consumer_key=" . self::CONSUMER_KEY . "&" .
                "oauth_nonce=" . $oauth_nonce . "&" .
                "oauth_signature_method=HMAC-SHA1&" .
                "oauth_timestamp=" . $oauth_timestamp . "&" .
                "oauth_version=1.0"
            );

        $key = self::CONSUMER_SECRET . "&";
        $oauth_signature = self::encode($oauth_base_text, $key);

        $url = self::URL_REQUEST_TOKEN .
            '?oauth_callback=' . urlencode(self::URL_CALLBACK) .
            '&oauth_consumer_key=' . self::CONSUMER_KEY .
            '&oauth_nonce=' . $oauth_nonce .
            '&oauth_signature=' . urlencode($oauth_signature) .
            '&oauth_signature_method=HMAC-SHA1' .
            '&oauth_timestamp=' . $oauth_timestamp .
            '&oauth_version=1.0';


        if (!($response = file_get_contents($url))) {
            return false;
        }
//        die("hello there");
        parse_str($response, $result);

        if (empty($result['oauth_token_secret'])) {
            return false;
        }

        $_SESSION['oauth_token_secret'] = $result['oauth_token_secret'];

        \Utils::redirect(self::URL_AUTHORIZE . '?oauth_token=' . $result['oauth_token']);
        return true;
    }

    private static function encode($string, $key) {
        return base64_encode(hash_hmac("sha1", $string, $key, true));
    }

    public static function getToken($oauth_token, $oauth_verifier) {
        $oauth_nonce = md5(uniqid(rand(), true));
        $oauth_timestamp = time();
        $oauth_token_secret = $_SESSION['oauth_token_secret'];

        $oauth_base_text = "GET&" .
            urlencode(self::URL_ACCESS_TOKEN) . "&" .
            urlencode(
                "oauth_consumer_key=" . self::CONSUMER_KEY . "&" .
                "oauth_nonce=" . $oauth_nonce . "&" .
                "oauth_signature_method=HMAC-SHA1&" .
                "oauth_token=" . $oauth_token . "&" .
                "oauth_timestamp=" . $oauth_timestamp . "&" .
                "oauth_verifier=" . $oauth_verifier . "&" .
                "oauth_version=1.0"
            );

        $key = self::CONSUMER_SECRET . "&" . $oauth_token_secret;
        $oauth_signature = self::encode($oauth_base_text, $key);

        $url = self::URL_ACCESS_TOKEN .
            '?oauth_consumer_key=' . self::CONSUMER_KEY .
            '&oauth_nonce=' . $oauth_nonce .
            '&oauth_signature_method=HMAC-SHA1' .
            '&oauth_token=' . urlencode($oauth_token) .
            '&oauth_timestamp=' . $oauth_timestamp .
            '&oauth_verifier=' . urlencode($oauth_verifier) .
            '&oauth_signature=' . urlencode($oauth_signature) .
            '&oauth_version=1.0';

        if (!($response = @file_get_contents($url))) {
            return false;
        }

        parse_str($response, $result);

        if (empty($result['oauth_token']) || empty($result['user_id'])) {
            return false;
        }

        self::$token = $result['oauth_token'];
        self::$userId = $result['user_id'];

        return true;
    }

    public function getUser() {
        $data = array(
            'grant_type' => 'client_credentials',
        );
        $opts = array('http' =>
            array(
                'method'  => 'POST',
                'header'  =>"Content-type: application/x-www-form-urlencoded;charset=UTF-8\r\n" .
                    'Authorization: Basic ' . base64_encode(self::CONSUMER_KEY . ':' . self::CONSUMER_SECRET) . "\r\n",
                'content' => http_build_query($data)
            )
        );
        $context  = stream_context_create($opts);

        if (!($response = @file_get_contents(self::URL_GET_TOKEN, false, $context))) {
            return false;
        }
        $result = json_decode($response, true);

        if (empty($result['access_token'])) {
            return false;
        }

        $opts = array('http' =>
            array(
                'method'  => 'GET',
                'header'  =>"Content-type: application/x-www-form-urlencoded;charset=UTF-8\r\n" .
                    'Authorization: Bearer ' . $result['access_token'] . "\r\n",
            )
        );

        $url = self::URL_USER_DATA . '?user_id=' . self::$userId;
        if (!($response = @file_get_contents($url, false, stream_context_create($opts)))) {
            return false;
        }

        $user = json_decode($response, true);
        if (empty($user)) {
            return false;
        }

        return self::$userData = $user;
    }

}

// Пример использования класса:
if (!empty($_GET['denied']))
    die('denied'); // Пользователь отменил авторизацию.
elseif (empty($_GET['oauth_token']) || empty($_GET['oauth_verifier']))
    OAuthTwitter::goToAuth();// Самый первый запрос
 else
 {
    // Пришёл ответ без ошибок после запроса авторизации
    $oauth_token = trim($_GET['oauth_token']);
    $oauth_verifier = trim($_GET['oauth_verifier']);
    if (!OAuthTwitter::getToken($oauth_token, $oauth_verifier))
        die('Error - no token by code');

    /*
     * На данном этапе можно проверить зарегистрирован ли у вас Twitter-юзер с id = OAuthTwitter::$userId
     * Если да, то можно просто авторизовать его и не запрашивать его данные.
     */

    $user = OAuthTwitter::getUser();
    print_r($user);
    /*
     * Вот и всё - мы узнали основные данные авторизованного юзера.
     * $user в этом примере состоит из многих полей: id, name, screen_name и т.д.
     */
}

