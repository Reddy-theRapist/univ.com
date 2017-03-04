<?php
//session_start();
require_once 'DB_Connection.php';
require_once '../person.inc';

if (isset($_POST["Commit"]))
{
    unset($_POST["Commit"]);

    $sql_head="insert into people (";
    $sql_tail="values (";
//    $debug="";
    $arguments=array(); $i=0;

    foreach ($_POST as $key=>$value)
    {
        $sql_head.=$key.",";
        $sql_tail.="?,";
        if ($key!="Birthday")
            $arguments[$i++]=$value;
        else $arguments[$i++]=date('Y-m-d', strtotime($value));
    }
    $sql_tail=substr($sql_tail,0,-1).")";
    $sql_head=substr($sql_head,0,-1).")"." ".$sql_tail;
//    $debug="sql string:".$sql_head.";";
    $errorcode=-1; $html=""; $errormessage="";

    try
    {
        $statement=$GLOBALS["DBH"]->prepare($sql_head);
        $statement->execute($arguments);
    }
    catch (PDOException $e) {
        $errorcode=$e->getCode();
        $errormessage=$e->getMessage();
    }

//    echo json_encode(array("DEBUG"=>$debug,"errorcode"=>$errorcode,"errormessage"=>$errormessage));
    echo json_encode(array("errorcode"=>$errorcode,"errormessage"=>$errormessage));
}
else {
        echo '<div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" title=\'Внесённые изменения не будут сохранены\'
                                            class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Добавление пользователя</h4>
                    </div>
                    
                    <div class="modal-body">
                        <form id="theForm" action=\'javascript:AddUser();\' method=\'post\'
                                                    onsubmit="return ValidateChanges()">
                            <div class="row">
                                <div class="form-group col-sm-4">
                                    <label class="control-label" for="fname">Имя:</label>
                                        <input required class="form-control anInput" id="fname" name="Name" type="text" 
                                                    value=""/>
                                </div>
                                <div class="form-group col-sm-4">
                                    <label class="control-label" for="mname">Отчество:</label>
                                        <input required class="form-control anInput" id="mname" name="MiddleName" type="text" 
                                                     value="" />
                                </div>
                                <div class="form-group col-sm-4">
                                    <label class="control-label" for="lname">Фамилия:</label>
                                        <input required class="form-control anInput" id="lname" name="LastName" type="text" 
                                                     value=""/>
                                </div>
                            </div> 
                            <hr/>
                            <div class="row">
                                <div class="form-group col-sm-4">
                                    <label class="control-label" for="sex">Пол:</label>
                                        <br>
                                        <div class="radio">
                                            <label>
                                                 <input required id="sex" class=\'anInput\' name="Sex" value="1" type="radio"';
                                                    echo " />Человек
                                            </label>
                                        </div>
                                        <div class='radio'>
                                            <label>
                                                <input required name=\"Sex\" class='anInput' value=\"0\" type=\"radio\"";
                                                    echo ' /> Женщина
                                            </label>
                                        </div> 
                                </div>
                                <div class="form-group col-sm-4">
                                    <label class="control-label" for="City">Город:</label>
                                        <input required class="form-control anInput" id="City" name="City" type="text" 
                                                    value=""/>
                                </div>
                                <div class="form-group col-sm-4">
                                    <label class="control-label" for="Region">Регион:</label>
                                        <select class="form-control anInput" id="region" name="Region" 
                                            >';
                                        $statement=$GLOBALS["DBH"]->prepare("SELECT DISTINCT Region FROM people");
                                        $statement->execute();
                                        while($region=$statement->fetch())
                                            echo "<option value='".$region[0]."'>$region[0]</option>";
                                        echo "</select>
                                </div>
                            </div>
                            <hr/>
                            <div class='row'>
                                <div class=\"form-group col-sm-4\">
                                    <label class=\"control-label\" for=\"email\">Email:</label>
                                        <input required class=\"form-control anInput\" id=\"email\" name=\"Email\" type='email'
                                         value=\"\"/>
                                </div>
                                <div class=\"form-group col-sm-4\">
                                    <label class=\"control-label\" for=\"pnumber\">Телефонама:</label>
                                        <input required class=\"form-control anInput\" id=\"pnumber\" name=\"PhoneNumber\"  
                                            type='tel' value=\"\" />
                                </div>
                                <div class=\"form-group col-sm-4\">
                                    <label class=\"control-label\" for=\"birthday\">День рождения:</label>
                                        <input required class=\"form-control anInput\" id=\"birthday\" name=\"Birthday\" type=\"date\" 
                                            value=\"\"/>
                                </div>
                            </div>";
                                echo "
                            <hr/>
                            <div class='row'>
                                <div class=\"form-group col-sm-4\">
                                    <label class=\"control-label\" for=\"job\">Должность:</label>
                                        <input required class=\"form-control anInput\" id=\"job\" name=\"Job\" type=\"text\" 
                                           value=\"\"/>
                                </div>
                                <div class='form-group col-sm-4'>
                                    <label class=\"control-label\" for=\"company\">Компания:</label>
                                        <input required class=\"form-control anInput\" id=\"company\" name=\"Company\" type=\"text\" 
                                               value=\"\" />
                                </div>
                                <div class='form-group col-sm-4'>
                                    <label class=\"control-label\" for=\"weight\">Вес (кг):</label>
                                        <input required class=\"form-control anInput\" id=\"weight\" name=\"Weight\" type=\"number\" 
                                                  value=\"\"/>
                                </div>
                            </div>
                            <hr/>
                            <div class='row'>
                                <div class=\"form-group col-sm-3\">
                                    <label class=\"control-label\" for=\"height\">Рост (см):</label>
                                        <input required class=\"form-control anInput\" id=\"height\" name=\"Height\" type=\"number\"
                                                    value=\"\"/>
                                </div>
                                <div class='form-group col-sm-3'>
                                    <label class=\"control-label\" for=\"mailingaddress\">Почтовый адрес:</label>
                                        <input required class=\"form-control anInput\" id=\"mailingaddress\" name=\"MailingAddress\" type=\"text\"
                                                    value=\"\" />
                                </div>
                                <div class='form-group col-sm-3'>
                                    <label for=\"postcode\">Почтовый индекс:</label>
                                        <input required class=\"form-control anInput\" id=\"postcode\" name=\"Postcode\" type=\"text\"
                                                   value=\"\"/>
                                </div>
                                <div class='form-group col-sm-3'>
                                    <label for=\"countrycode\">Код страны:</label>
                                        <input required class=\"form-control anInput\" id=\"countrycode\" name=\"Country\" type=\"text\"
                                               value=\"\"/>
                                </div>
                            </div>
                            <hr/>"; echo '
                            <div class="row">
                                <div class="form-group col-sm-4 col-sm-offset-4">
                                    <input type="submit" class="form-control" name="Commit"
                                           value="Добавить"  >
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