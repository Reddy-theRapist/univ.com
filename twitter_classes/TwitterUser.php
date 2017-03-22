<?php
/**
 * Created by PhpStorm.
 * User: YoloSwaggerson
 * Date: 17.03.2017
 * Time: 19:20
 */
require "twitter_model.php";

class TwitterUser extends twitter_model
{
    public $contributors_enabled;
    public $created_at;
    public $default_profile;
    public $default_profile_image;
    public $description;
    public $entities;
    public $favourites_count;
    public $follow_request_sent;
    public $following;
    public $followers_count;
    public $friends_count;
    public $geo_enabled;
    public $id_str="-1";
    public $is_translator;
    public $lang="";
    public $listed_count=-1;
    public $location="";
    public $notifications=false;
    public $profile_background_color="";
    public $id=-1;
    public $name="";
    public $profile_background_image_url="";
    public $profile_background_image_url_https;
    public $profile_background_tile;
    public $profile_banner_url;
    public $profile_image_url;
    public $profile_image_url_https;
    public $profile_link_color;
    public $profile_sidebar_border_color;
    public $profile_sidebar_fill_color;
    public $profile_text_color;
    public $profile_use_background_image;
    public $protected;
    public $screen_name;
    public $show_all_inline_media;
    public $status;
    public $statuses_count;
    public $time_zone;
    public $url;
    public $utc_offset;
    public $verified;
    public $withheld_in_countries;
    public $withheld_scope;

//    private $AccessToken="";
//    public $initialized = false;
    private $accessToken="";
    public $followers=array();
    public $followed= array();
    public $advices=array();

    function setAccessToken($newAT)
    {
        if ($this->accessToken!=="")
        {
            $this->accessToken=$newAT;
            return true;
        }
        else return false;
    }


    function __construct()
    {
//        $this->AccessToken=$AT;
    }
}