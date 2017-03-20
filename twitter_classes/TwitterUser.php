<?php
/**
 * Created by PhpStorm.
 * User: YoloSwaggerson
 * Date: 17.03.2017
 * Time: 19:20
 */
class TwitterUser
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
    public $initialized = false;


    function CreateFromJSON($data)
    {
        if (count($data)===0)
            return null;

        $mismatched =  array();

        foreach ($data as $key=>$datum) {
            if (property_exists($this,$key))
                $this->$key=$datum;
            else $mismatched[$key]=$datum;
        }

        $this->initialized=true;

        if (count($mismatched)>0)
            return "mismatch between json data and user class definition:".print_r($mismatched, true).'<br>';
        return false;
    }

    function __construct()
    {
//        $this->AccessToken=$AT;
    }
}