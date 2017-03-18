<?php

if (isset($_POST["COMMIT"])) {
    $errorcode=-1;
    $PDH;
    $errormessage="";
    $ClientIndex=-1;
//    $SYNC_ARRAY=array();
    $syncIndex=0;

    $debuginfo="recieved package: IDs=".$_POST["IDs"]." Quantities=".$_POST["Quantities"].", Client name= ".$_POST["ClientName"].
        ", pnumber=".$_POST["Phone"].
        ", RentTime=".$_POST["RentTime"].", ArrivalDateTime=".$_POST["ArrivalDateTime"];
    try
    {
        $PDH = pg_connect("host=localhost port=5432 dbname=RentService user=postgres password=123");
    } catch (Exception $e) {$errorcode=$e->getCode(); $errormessage=$e->getMessage();}

    $IDs=explode(";",$_POST["IDs"]);
    $Quantities=explode(";",$_POST["Quantities"]);

    try
    {
//        $Q=pg_query($PDH, 'select max(ID) from "Клиент"');
//        $max_id=pg_fetch_row($Q,0)[0];

        $rezult = pg_prepare($PDH, "getClientID", 'select "CheckUserExists_Function"($1, $2)');
        $rezult = pg_execute($PDH, "getClientID", array($_POST["ClientName"], $_POST["Phone"]));

        $ClientIndex=pg_fetch_row($rezult,0)[0];

//        if ($max_id<$ClientIndex)
//            $SYNC_ARRAY[$syncIndex++]= str_replace(' ','_','Клиент;'.$_POST["ClientName"].','.$_POST["Phone"].'NULL');
    }
    catch (Exception $e) {$errorcode=$e->getCode(); $errormessage.='; '.$e->getMessage();}
    $debuginfo.='client id = '.$ClientIndex;

//    try
//    {
//        $param1=str_replace('T',' ', $_POST["ArrivalDateTime"]);
//        $param2=$_POST["RentTime"];
//        $query='insert into "Заказ" ("Дата и Время получения","Срок аренды","Клиент")
//                  values ('."'".$param1."'".', '.$param2.', '.$ClientIndex.') returning "ID"';
//
////        $SYNC_ARRAY[$syncIndex++]=str_replace(" ","_",'Заказ;'.$param1.','.$param2.',NULL,'.$ClientIndex.',0,0,NULL');
//
//        $rezult = pg_query($PDH, $query);
//        $LastOrder=pg_fetch_row($rezult,0)[0];
//
//
//        for ($i = 0;$i<count($IDs);$i++)
//            for ($j = 0;$j<$Quantities[$i];$j++)
//            {
//                $query = 'insert into "Инвентарь_в_заказе" ("Инвентарь", "Заказ") values ('.$IDs[$i].','.$LastOrder.')';
//                $rezult=pg_query($PDH,$query);
////                $SYNC_ARRAY[$syncIndex++]=str_replace(' ','_','Инвентарь_в_заказе;'.$IDs[$i].','.$LastOrder.',NULL, 0');
//            }
//
//    } catch (Exception $e){$errormessage.=$e->getMessage(); $errorcode=$e->getCode(); }
//        $executionString = 'E:\executor\ConsoleApplication3\ConsoleApplication3\bin\Debug\ConsoleApplication3.exe ';
//    for ($x=0;$x<count($SYNC_ARRAY);$x++)
//        $executionString.=$SYNC_ARRAY[$x]." ";
//    exec($executionString);

    echo json_encode(array("debug"=>$debuginfo, "errorcode"=>0, "errormessage"=>"checkthat"));
}
else echo json_encode(array("debug"=>"null", "errorcode"=>0, "errormessage"=>"commit wasn't set"));