<?php
$TableLoaded=false;
$CurrentPage=-1;
$MAX_PAGE=-1;
$RecordsPerPage=10;
$TotalRecords=0;
$count_statement=$GLOBALS["DBH"]->prepare("select count(1) from people");
$count_statement->execute();
$MAX_PAGE = $count_statement->fetchColumn()/$RecordsPerPage;

if (isset($_POST["Create_N_Fill_table"]))
   CreateTable();

function ReadDB($page=0, $FetchType)
{
    $error_message=-1;
    $rezult="";
    global $RecordsPerPage, $BY_PAGE, $ON_SCROLL;

    if ($page>=$GLOBALS["MAX_PAGE"])
        $error_message=1000;

    if ($error_message==-1)
    {
        $statement=$GLOBALS["DBH"]->prepare("select ID, Name, MiddleName, LastName, Region, City, Birthday, PhoneNumber from people LIMIT ?, ?");
        $statement->bindValue(1, $page*$RecordsPerPage, PDO::PARAM_INT);
        $statement->bindValue(2, $RecordsPerPage, PDO::PARAM_INT);
        $statement->execute();

        $statement->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE,"Person",[null,null,null,null,null,null,null,null,
                                                                                    null,null,null,null,null,null,null,null,null]);

        if ($FetchType==$BY_PAGE&&$error_message!=1000)
        $rezult.= "<table class='table table-responsive table-striped table-bordered table-hover'>
                <thead>
                <tr>
                    <th width=\"4%\" >ID</th>
                    <th width=\"20%\" >Name</th>
                    <th width=\"35\">Home town</th>
                    <th width=\"5%\">Age</th>
                    <th width=\"20%\">Contacts</th>
                    <th width=\"14%\">Actions</th>
                </tr>
                </thead>
                <tbody>";

        while ($P = $statement->fetch())
        {
            $rezult.= "<tr>";
            $rezult.="<td class='theID'>".$P->ID."</td>";
            $rezult.="<td>".$P->FullName()."</td>";
            $rezult.="<td>".$P->City." ".$P->Region. "</td>";
            $rezult.="<td>".$P->GetCurrentAge("YYYY")."</td>";
            $rezult.="<td>".$P->PhoneNumber."</td>";
            $rezult.="<td>
                    <div class='btn-group-vertical' style='width: 100%;'>
                        <button type='button' class='btn btn-info' onclick='ShowModalWindow(1, $(this).parents(\"td\").first().siblings(\".theID\").html());'>Edit</button>
                        <button type='button' class='btn btn-warning' onclick='ShowModalWindow(2,$(this).parents(\"td\").first().siblings(\".theID\").html());'>Delete</button>
                    </div> 
                  </td>";
            $rezult.= "</ tr>";
        }
        if ($FetchType==$BY_PAGE&&$error_message!=1000)
            $rezult.="</tbody> </table> <div><hr/>";
    }

//    $rezult=addslashes($rezult);
    echo json_encode(array("HTML"=>$rezult, "error"=>$error_message));
//    echo '<ul class="pager">';
//    if($page>0)
//            echo '<li class="previous"><a href="#" onclick="FetchNextPage(false);" id="previouspage">Previous</a></li>';
//    if($page<$GLOBALS["MAX_PAGE"])
//        echo '<li class="next"><a href="#" onclick="FetchNextPage(true);"  id="nextpage">Next</a></li>';
//    echo '</ul>';
    $GLOBALS["TableLoaded"]=true;
}


function GetRecord(&$ID)
{
    $error_message=-1;
    $rezult="";
    global $RecordsPerPage;

    if ($ID>$GLOBALS["MAX_PAGE"]*$RecordsPerPage)
        $error_message=1000;

    if ($error_message==-1)
    {
        $statement = $GLOBALS["DBH"]->prepare("select ID, Name, MiddleName, LastName, Region, City, Birthday, PhoneNumber from people WHERE ID=?");
        $statement->bindValue(1, $ID, PDO::PARAM_INT);
        $statement->execute();

        $statement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "Person", [null, null, null, null, null, null, null, null,
            null, null, null, null, null, null, null, null, null]);

        while ($P = $statement->fetch()) {
            $rezult .= "<tr>";
            $rezult .= "<td class='theID'>" . $P->ID . "</td>";
            $rezult .= "<td>" . $P->FullName() . "</td>";
            $rezult .= "<td>" . $P->City . " " . $P->Region . "</td>";
            $rezult .= "<td>" . $P->GetCurrentAge("YYYY") . "</td>";
            $rezult .= "<td>" . $P->PhoneNumber . "</td>";
            $rezult .= "<td>
                    <div class='btn-group-vertical' style='width: 100%;'>
                        <button type='button' class='btn btn-info' onclick='ShowModalWindow(1, $(this).parents(\"td\").first().siblings(\".theID\").html());'>Edit</button>
                        <button type='button' class='btn btn-warning' onclick='ShowModalWindow(2,$(this).parents(\"td\").first().siblings(\".theID\").html());'>Delete</button>
                    </div> 
                  </td>";
            $rezult .= "</ tr>";
        }
    }
    echo json_encode(array("HTML"=>$rezult, "error"=>$error_message));
}




function CreateTable()
{
    $id="";$name="";$mname=""; $lname=""; $sex=0; $city="";
    $region="";$email=""; $pnumber="";$bday=""; $job=""; $company="";
    $weight=0;$height=0;$maddress=""; $postcode=""; $country="";
    $sql1 = "DROP table if EXISTS People";
    $sql2 = "CREATE TABLE IF NOT EXISTS People
            (
              ID int(11) NOT NULL PRIMARY KEY,
              Name varchar(100) NOT NULL,
              MiddleName varchar(1) NOT NULL,
              LastName varchar(100) NOT NULL,
              Sex char(1) NOT NULL,
              City varchar(100) NOT NULL,
              Region varchar(2) NOT NULL,
              Email varchar(100) NOT NULL,
              PhoneNumber varchar(12) NOT NULL,
              Birthday date NOT NULL,
              Job varchar(100) NOT NULL,
              Company varchar(100) NOT NULL,
              Weight float(11) NOT NULL,
              Height float(11) NOT NULL,
              MailingAddress varchar(100) NOT NULL,
              Postcode varchar(10) NOT NULL,
              Country varchar(2) NOT NULL
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

    $statement = $GLOBALS["DBH"]->prepare($sql1);
    $statement->execute();
    $statement = $GLOBALS["DBH"]->prepare($sql2);
    $statement->execute();
    $statement=$GLOBALS["DBH"]->prepare("insert into people (ID, Name, MiddleName, LastName, Sex, City,Region, Email,
                                          PhoneNumber,Birthday,Job,Company,Weight,Height,MailingAddress,Postcode,Country)
                                          values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);");
    $statement->bindParam(1, $id); $statement->bindParam(2,$name); $statement->bindParam(3,$mname);
    $statement->bindParam(4,$lname); $statement->bindParam(5,$sex); $statement->bindParam(6,$city);
    $statement->bindParam(7,$region); $statement->bindParam(8,$email); $statement->bindParam(9,$pnumber);
    $statement->bindParam(10, $bday,PDO::PARAM_STR); $statement->bindParam(11,$job); $statement->bindParam(12,$company);
    $statement->bindParam(13,$weight); $statement->bindParam(14,$height); $statement->bindParam(15,$maddress);
    $statement->bindParam(16,$postcode);                          $statement->bindParam(17,$country);

    $handle= fopen("csvs/newone.txt","r");
    while (!feof($handle))
    {
        $fields = explode(";",fgets($handle));
        if (count($fields)>0)
        {
//        {for ($i = 1;$i<=count($fields);$i++)
//                $statement->bindValue($i,$fields[$i-1]);
            $id=$fields[0]; $name=$fields[1];$mname=$fields[2]; $lname=$fields[3]; $sex=$fields[4];
            $city=$fields[5];$region=$fields[6]; $email=$fields[7]; $pnumber=$fields[8];
            $bday=date('Y-m-d',strtotime($fields[9]));
            $job=$fields[10]; $company=$fields[11]; $weight=$fields[12]; $height=$fields[13]; $maddress=$fields[14];
            $postcode=$fields[15]; $country=$fields[16];

        try
        {$statement->execute();}
        catch (PDOException $e) {echo $e->getMessage();}
        }
    }
    fclose($handle);
}
function tableExists($table) {

    try
    {$result = $GLOBALS["DBH"]->query("SELECT 1 FROM $table LIMIT 1");}
    catch (Exception $e) {return false;}

    // Result is either boolean FALSE (no table found) or PDOStatement Object (table found)
    return true;
}
?>