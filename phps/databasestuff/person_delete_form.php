<?php
session_start();
require_once 'DB_Connection.php';
require_once '../person.inc';
//$rezult="";
if (isset($_POST["Commit"]))
{
    $errorcode=-1; $errormessage="";
    try {
        $statement = $GLOBALS["DBH"]->prepare("delete from people where ID=?");
        $statement->execute(array($_SESSION["CurrentID"]));
    }
    catch (PDOException $e) {
        $errormessage=$e->getMessage();
        $errorcode=$e->getCode();
    }
    echo json_encode(array("errorcode"=>$errorcode,"errormessage"=>$errormessage));
    unset($_SESSION["CurrentID"]);
}
if (isset($_GET["pID"]))
{
    $_SESSION["CurrentID"]=$_GET["pID"];
    $html="";$errorcode=-1;$errormessage="";
    try
    {
        $statement = $GLOBALS["DBH"]->prepare("select ID, Name, MiddleName, LastName from people where ID=? LIMIT 1");
        $statement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "Person", [null, null, null, null, null, null, null, null,
            null, null, null, null, null, null, null, null, null]);
        $statement->execute(array($_GET["pID"]));
    } catch (PDOException $e){ $errorcode=$e->getCode(); $errormessage=$e->getMessage();}
    if ($P = $statement->fetch())
        $html = '<div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" title=\'Внесённые изменения не будут сохранены\'
                                            class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Удаление данных пользователя: ID:'. $P->ID.', Имя:'.$P->FullName(). '</h4>
                        </div>
                    
                        <div class="modal-body">
                            <form action="javascript: CommitDeletion('.$_SESSION["CurrentID"].')" method=\'post\'">
                                <input type="submit" class="btn btn-default" name="Commit" value="Подтвердите удаление "/>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Я передумал</button>
                        </div>
                    </div>
                </div>';
    echo json_encode(array("HTML"=>$html, "errorcode"=>$errorcode, "errormessage"=>$errormessage));
}
?>
