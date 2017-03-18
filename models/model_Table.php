<?php
/**
 * Created by PhpStorm.
 * User: YoloSwaggerson
 * Date: 10.03.2017
 * Time: 0:25
 */
require 'Content.php';

class Table
{
    private $tableName;
    private $dbname;
    private $db_password;
    private $db_host;
    private $db_user;
    /*
     * 0 == pure
     * 1 == correctly loaded all rows
     * 2 == an error occured when loading table from db
     * TODO: extend error codes based on kind of exception: not enough privilleges, not existing db/table etc
     */
    public $modelState=0;

    function __construct($tableName, $dbname, $db_password="", $db_host="localhost", $db_user="root")
    {
        $this->tableName=$tableName;
        $this->dbname=$dbname;
        $this->db_password=$db_password;
        $this->db_host=$db_host;
        $this->db_user=$db_user;
    }

    function ConnectToDB(&$DBH)
    {
        try
        {
           $DBH=new PDO("mysql:host=".$this->db_host.";dbname=".$this->dbname,$this->db_user,$this->db_password);
            $DBH->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $DBH->query( "SET CHARSET utf8" );
           return 1;
        }
        catch (Exception $e)
        {return 2;}


    }

    function GetAllRows()
    {
        $DBH=null;
        if ($this->ConnectToDB($DBH)==1)
        {
            $statement=$DBH->prepare("select * from $this->tableName");
            $statement->execute();
            $statement->setFetchMode(PDO::FETCH_ASSOC);
            $data=$statement->fetchAll();
            return $data;
        }
    }

}