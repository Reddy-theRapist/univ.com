<?php
session_start();
require_once 'DB_Connection.php';
require_once '../person.inc';

if (isset($_POST["Commit"]))
{
    unset($_POST["Commit"]);
//    echo json_encode(array("debug"=>"WORKS TILL HERE."));
    $sql = "UPDATE people set ";
//    $Parameters=explode(";",$_POST["ChangedValues"]);
//    $_POST["ChangedValues"]=null;//?
    $errorcode=-1; $html=""; $errormessage="";

    foreach ($_POST as $key=>$value)
        $sql.=$key."= ?,";

    $sql = substr($sql,0,-1);
    $sql.=" where ID=?";

    $arguments=array(); $i=0;
    foreach ($_POST as $key=>$value)
    {
        if ($key == "Birthday")
            $value = date('Y-m-d', strtotime($value));
        $arguments[$i++]=$value;
    }

    $arguments[count($arguments)]=$_SESSION["CurrentID"];
    try
    {
        $statement=$GLOBALS["DBH"]->prepare($sql);
//        $statement->bindParam(count($arguments)+1,$_SESSION["CurrentID"],PDO::PARAM_INT);
        $statement->execute($arguments);

        $statement=$GLOBALS["DBH"]->prepare("select ID, Name, MiddleName, LastName, Region, City, Birthday, PhoneNumber from people where ID=?");
        $statement->execute(array($_SESSION["CurrentID"]));

        $statement->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE,"Person",[null,null,null,null,null,null,null,null,
            null,null,null,null,null,null,null,null,null]);

        $P = $statement->fetch();
        $html = GetTableRow($P);
    }
    catch (PDOException $e) {
        $errorcode=$e->getCode();
        $errormessage=$e->getMessage();
    }
    unset($_SESSION["CurrentID"]);

    echo json_encode(array("HTML"=>$html,"errorcode"=>$errorcode,"errormessage"=>$errormessage));
}
if (isset($_GET["pID"])) {
    $_SESSION["CurrentID"]=$_GET["pID"];

    $statement = $GLOBALS["DBH"]->prepare("select * from people where ID=? LIMIT 1");
    $statement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "Person", [null, null, null, null, null, null, null, null,
        null, null, null, null, null, null, null, null, null]);
    $statement->execute(array($_GET["pID"]));
    if ($P = $statement->fetch())
    {
        echo '<div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" title=\'Внесённые изменения не будут сохранены\'
                                            class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Изменение данных пользователя</h4>
                    </div>
                    
                    <div class="modal-body">
                        <form id="theForm" action=\'javascript:SendChanges($("#displayID").val());\' method=\'post\'
                                                    onsubmit="return ValidateChanges()">
                            <div class="row">
                                <div class="form-group col-sm-4 col-sm-offset-4">
                                    <label class="control-label" for="ID">ID:</label>
                                        <input class="form-control" id="displayID" value="'.$P->ID.'" type="number" readonly />
                                </div>
                            </div>
                            <br/>
                            <div class="row">
                                <div class="form-group col-sm-4">
                                    <label class="control-label" for="fname">Имя:</label>
                                        <input required class="form-control anInput" id="fname" name="Name" type="text" 
                                                    onchange="ChangeReg(this.name);" value="'.$P->Name.'"/>
                                </div>
                                <div class="form-group col-sm-4">
                                    <label class="control-label" for="mname">Отчество:</label>
                                        <input required class="form-control anInput" id="mname" name="MiddleName" type="text" 
                                                    onchange="ChangeReg(this.name);" value="'.$P->MiddleName.'" />
                                </div>
                                <div class="form-group col-sm-4">
                                    <label class="control-label" for="lname">Фамилия:</label>
                                        <input required class="form-control anInput" id="lname" name="LastName" type="text" 
                                                    onchange="ChangeReg(this.name);" value="'.$P->LastName.'"/>
                                </div>
                            </div> 
                            <hr/>
                            <div class="row">
                                <div class="form-group col-sm-4">
                                    <label class="control-label" for="sex">Пол:</label>
                                        <br>
                                        <div class="radio">
                                            <label>
                                                 <input required class="anInput" id="sex" onchange="ChangeReg(this.name);" name="Sex" value="1" type="radio"';
                                                    if ($P->Sex==1)
                                                        echo " checked ";
                                                echo " />Человек
                                            </label>
                                        </div>
                                        <div class='radio'>
                                            <label>
                                                <input required name=\"Sex\" class='anInput' onchange=\"ChangeReg(this.name);\" value=\"0\" type=\"radio\"";
                                                    if ($P->Sex==0) echo " checked ";
                                                echo ' /> Женщина
                                            </label>
                                        </div> 
                                </div>
                                <div class="form-group col-sm-4">
                                    <label class="control-label" for="City">Город:</label>
                                        <input required class="form-control anInput" id="City" name="City" type="text" 
                                                    onchange="ChangeReg(this.name);" value="'.$P->City.'"/>
                                </div>
                                <div class="form-group col-sm-4">
                                    <label class="control-label" for="Region">Регион:</label>
                                        <select class="form-control" id="region" name="Region" 
                                            onchange="ChangeReg(this.name);">';
                                                $statement=$GLOBALS["DBH"]->prepare("SELECT DISTINCT Region FROM people");
                                                $statement->execute();
                                                while($region=$statement->fetch())
                                                if ($region[0]!=$P->Region)
                                                    echo "<option value='".$region[0]."'>$region[0]</option>";
                                                else echo "<option selected value='".$region[0]."'>$region[0]</option>";
                                    echo "</select>
                                </div>
                            </div>
                            <hr/>
                            <div class='row'>
                                <div class=\"form-group col-sm-4\">
                                    <label class=\"control-label\" for=\"email\">Email:</label>
                                        <input required class=\"form-control anInput\" id=\"email\" name=\"Email\" type='email'
                                         onchange=\"ChangeReg(this.name);\" value=\"".$P->Email."\"/>
                                </div>
                                <div class=\"form-group col-sm-4\">
                                    <label class=\"control-label\" for=\"pnumber\">Телефонама:</label>
                                        <input required class=\"form-control anInput\" id=\"pnumber\" name=\"PhoneNumber\"  
                                            onchange=\"ChangeReg(this.name);\" type='tel' value=\"".$P->PhoneNumber."\" />
                                </div>
                                <div class=\"form-group col-sm-4\">
                                    <label class=\"control-label\" for=\"birthday\">День рождения:</label>
                                        <input required class=\"form-control anInput\" id=\"birthday\" name=\"Birthday\" type=\"date\" 
                                            onchange=\"ChangeReg(this.name);\" value=\"".$P->Birthday."\"/>
                                </div>
                            </div>";
                                echo "
                            <hr/>
                            <div class='row'>
                                <div class=\"form-group col-sm-4\">
                                    <label class=\"control-label\" for=\"job\">Должность:</label>
                                        <input required class=\"form-control anInput\" id=\"job\" name=\"Job\" type=\"text\" 
                                           onchange=\"ChangeReg(this.name);\" value=\"".$P->Job."\"/>
                                </div>
                                <div class='form-group col-sm-4'>
                                    <label class=\"control-label\" for=\"company\">Компания:</label>
                                        <input required class=\"form-control anInput\" id=\"company\" name=\"Company\" type=\"text\" 
                                               onchange=\"ChangeReg(this.name);\" value=\"".$P->Company."\" />
                                </div>
                                <div class='form-group col-sm-4'>
                                    <label class=\"control-label\" for=\"weight\">Вес (кг):</label>
                                        <input required class=\"form-control anInput\" id=\"weight\" name=\"Weight\" type=\"number\" 
                                                  onchange=\"ChangeReg(this.name);\"  value=\"".$P->Weight."\"/>
                                </div>
                            </div>
                            <hr/>
                            <div class='row'>
                                <div class=\"form-group col-sm-3\">
                                    <label class=\"control-label\" for=\"height\">Рост (см):</label>
                                        <input required class=\"form-control anInput\" id=\"height\" name=\"Height\" type=\"number\"
                                                    onchange=\"ChangeReg(this.name);\" value=\"".$P->Height."\"/>
                                </div>
                                <div class='form-group col-sm-3'>
                                    <label class=\"control-label\" for=\"mailingaddress\">Почтовый адрес:</label>
                                        <input required class=\"form-control anInput\" id=\"mailingaddress\" name=\"MailingAddress\" type=\"text\"
                                                    onchange=\"ChangeReg(this.name);\" value=\"".$P->MailingAddress."\" />
                                </div>
                                <div class='form-group col-sm-3'>
                                    <label for=\"postcode\">Почтовый индекс:</label>
                                        <input required class=\"form-control anInput\" id=\"postcode\" name=\"Postcode\" type=\"text\"
                                                   onchange=\"ChangeReg(this.name);\" value=\"".$P->Postcode."\"/>
                                </div>
                                <div class='form-group col-sm-3'>
                                    <label for=\"countrycode\">Код страны:</label>
                                        <input required class=\"form-control anInput\" id=\"countrycode\" name=\"Country\" type=\"text\"
                                               onchange=\"ChangeReg(this.name);\" value=\"". $P->Country."\"/>
                                </div>
                            </div>
                            <hr/>"; echo '
                            <div class="row">
                                <div class="form-group col-sm-4 col-sm-offset-4">
                                    <input type="submit" class="form-control" name="Commit"
                                           value="Применить изменения"  >
                                           <input type="hidden" name="ChangedValues" id="ChangedValues"
                                           value=""  >
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                    </div>
                </div>
            </div>';
    }
}
function GetTableRow(&$P)
{
    $html="";
    $html.="<td class='theID'>".$P->ID."</td>";
    $html.="<td>".$P->FullName()."</td>";
    $html.="<td>".$P->City." ".$P->Region. "</td>";
    $html.="<td>".$P->GetCurrentAge("YYYY")."</td>";
    $html.="<td>".$P->PhoneNumber."</td>";
    $html.="<td>
                <div class='btn-group-vertical' style='width: 100%;'>
                    <button type='button' class='btn btn-info' onclick='ShowModalWindow(1, $(this).parents(\"td\").first().siblings(\".theID\").html());'>Edit</button>
                    <button type='button' class='btn btn-warning' onclick='ShowModalWindow(2,$(this).parents(\"td\").first().siblings(\".theID\").html());'>Delete</button>
                </div> 
              </td>";
    return $html;
}
?>
