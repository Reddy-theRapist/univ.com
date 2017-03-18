<?php
require_once '../person.inc';
require_once 'DB_Connection.php';
require_once 'databaseLogic.php';
$BY_PAGE=1; $ON_SCROLL=2;
if (isset($_GET["page"])&&isset($_GET["FetchType"]))
        ReadDB($_GET["page"],$_GET["FetchType"]);
else if(isset($_GET["ID"]))
        GetRecord($_GET["ID"]);
?>