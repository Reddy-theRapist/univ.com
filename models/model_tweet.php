<?php

/**
 * Created by PhpStorm.
 * User: YoloSwaggerson
 * Date: 05.03.2017
 * Time: 14:13
 */
class model_Tweet Extends Model_Base
{
    public $contributors;
public $coordinates;
public $created_at;
public $current_user_retweet;
public $entities;
public $favorite_count;
public $favorited;
public $filter_level;
public $geo;
public $id;
public $id_str;
public $in_reply_to_screen_name;
public $in_reply_to_status_id;
public $in_reply_to_status_id_str;
public $in_reply_to_user_id;
public $in_reply_to_user_id_str;
public $lang;
public $place;
public $possibly_sensitive;
public $quoted_status_id;
public $quoted_status_id_str;
public $quoted_status;
public $scopes;
public $retweet_count;
public $retweeted;
public $retweeted_status;
public $source;
public $text;
public $truncated;
public $user;
public $withheld_copyright;
public $withheld_in_countries;
public $withheld_scope;
    // далее описать все остальные поля сущности

    public function fieldsTable()
    {
        return array(
            'id' => 'Id',
            // здесь тоже лучше не забыть все описать
        );
    }
}