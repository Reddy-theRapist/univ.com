<?php
$DBH;
try
{
//    $GLOBALS["DBH"]=new PDO("mysql:host=localhost;dbname=6l","theRoot","4Vv63BtBAWV7rGsy");
    $GLOBALS["DBH"]=new PDO("mysql:host=localhost;dbname=6l","root","");
    $GLOBALS["DBH"]->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//    $DBH->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);//это для корректной работы лимита
}
catch(PDOException $e)
{
    echo $e->getMessage();
    die ('<br>error code: pizdec');
}
//
//$sql = "CREATE TABLE IF NOT EXISTS People
//(
//    ID int(11) NOT NULL PRIMARY KEY,
//  FirstName varchar(100) NOT NULL,
//  MiddleName varchar(1) NOT NULL,
//  LastName varchar(100) NOT NULL,
//  Sex char(1) NOT NULL,
//  City varchar(100) NOT NULL,
//  State varchar(2) NOT NULL,
//  Email varchar(100) NOT NULL,
//  PhoneNumber varchar(12) NOT NULL,
//  Birthday date NOT NULL,
//  Job varchar(100) NOT NULL,
//  Company varchar(100) NOT NULL,
//  Weight float(11) NOT NULL,
//  Height float(11) NOT NULL,
//  MailingAddress varchar(100) NOT NULL,
//  Postcode varchar(10) NOT NULL,
//  Country varchar(2) NOT NULL
//) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
//
//$GLOBALS["DBH"]->exec($sql);
//    $sql = "CREATE USER \'theRoot\'@\'localhost\' IDENTIFIED VIA mysql_native_password USING \'***\'
//    GRANT ALL PRIVILEGES ON *.* TO \'theRoot\'@\'localhost\' REQUIRE NONE WITH GRANT OPTION MAX_QUERIES_PER_HOUR 0
//    MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0 GRANT ALL PRIVILEGES ON
//    `theRoot\\_%`.* TO \'theRoot\'@\'localhost\'";
//    $DBH=new PDO("mysql:host=???;dbname=???6L","user???","password???");

?>

