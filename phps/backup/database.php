<?php
session_start();
if (isset($_SESSION["CurrentID"]))
    unset($_SESSION["CurrentID"]);
require_once 'person.inc';
include_once 'skeleton_firstpart.html';
require_once 'databasestuff/DB_Connection.php';
require_once 'databasestuff/databaseLogic.php';
//require_once 'databasestuff/GetTablePage.php';
//$_GET["page"]=-1;
?>

<div class="row extendedContainer" id="theContainer">

    <div class="col-sm-3">
        <h4 style="text-align:center">Навигация</h4>
        <!-- <nav class="col-sm-3"> -->
        <ul class="nav nav-pills nav-stacked extendedUL" id="NabnarItems">
            <li><button class="btn btn-primary extendedBtn-bottom" onclick="scrollTowards('start')">В начало конца</button></li>
            <li><button class="btn btn-primary extendedBtn-top" onclick="scrollTowards('end')">В конец начала</button></li>
        </ul>
        <!-- </nav> -->
        <br/>
        <div class="input-group extendedUL">
            <input type="text" class="form-control" placeholder="Поиск по сайту..">
            <span class="input-group-btn">
	          <button class="btn btn-default" type="button">
	          <span class="glyphicon glyphicon-search"></span>
			</button>
			</span>
        </div>
    </div>

    <div class="col-sm-9">
        <?php
            $x=-1;
            $doesExists=false;
            if (tableExists("people"))
            {
                $doesExists=true;
                $x=$GLOBALS["DBH"]->prepare("select count(1) from people");
                $x->execute();
                $TotalRecords=$x->fetchColumn();
            }

            if (!$doesExists||$TotalRecords==0):
        ?>
            <form style="margin:10px;" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                    <div class="form-group">
                        <input style="margin:5px;" type="submit" name="Create_N_Fill_table" value="Заполнить БД" />
                    </div>
                    <hr/>
                </form>

            <?php  elseif(!$TableLoaded):?>
                <button style="margin-top: 30px;" type="button" id="requestTable"  onclick="FetchFirstPage(); "
                        data-trigger="hover" data-toggle="popover" header="TIP"
                        data-content="По <?php echo $GLOBALS["RecordsPerPage"];?> рыл за раз."
                            class="btn btn-primary">Показать таблицу человеков <span class="badge"><?php echo $TotalRecords; ?></span>
                </button>
                 <hr/>
        <div id="tableDIV" style="overflow-x: auto; width: 100%;"> </div>
        <!--it was here -->
    <?php endif; ?>
    </div>
</div>

<?php
echo
'<script type="text/javascript">
	$("#phptest > a").css("color","rgb(252, 163, 90)");
	$(document).ready(function()
	{
        $(\'[data-toggle="popover"]\').popover({delay:{show:1000,hide:100}});   
    });
</script>';
include_once 'skeleton_lastpart.html';
?>
