<?php
include_once 'skeleton_firstpart.html';
include_once '../htmls/inventory.html';
include_once '../Templates/SubmitOrder.tpl';
echo '<h3>Ниже представлен список наших товаров</h3><hr/>';
$PDH=pg_connect("host=localhost port=5432 dbname=RentService user=postgres password=123")
     or die('Не удалось подключиться к БД: ' . pg_last_error());
$query = 'select * from "Инвентарь_View"';
$rezult = pg_query($PDH,$query);

echo
    '<form id="theForm" action=\'javascript:SubmitOrder();\'>
        
        <table class="table table-striped table-bordered table-hover table-responsive"  id="theTable"
                        data-toggle="table" data-sort-name="invType" data-sort-order="desc">
        <thead>
            <tr>
                <th data-field="ID" data-sortable="true"><div class="th-inner sortable both">ID</div><div class="fht-cell"></div></th>
                <th data-field="invType" data-sortable="true"><div class="th-inner sortable both">Тип инвентаря</div><div class="fht-cell"></div></th>
                <th data-field="inv" data-sortable="true"><div class="th-inner sortable both">Инвентарь</div><div class="fht-cell"></div></th>
                <th data-field="gender" data-sortable="true"><div class="th-inner sortable both">Пол</div><div class="fht-cell"></div></th>
                <th data-field="pricePerHour" data-sortable="true"><div class="th-inner sortable both">Цена/час</div><div class="fht-cell"></div></th>
                <th data-field="AvailableCount" data-sortable="true"><div class="th-inner sortable both">Доступно  (шт)</div><div class="fht-cell"></div></th>
                <th data-field="checkbox" data-sortable="false">Отметить</th>
                <th data-field="quantity" data-sortable="false">Кол-во</th>
            </tr>
        </thead>
        <tbody>';
        while ($row = pg_fetch_assoc($rezult))
            echo
            '<tr data-index="'.$row["ID"].'">
                <td class="theID">'.$row["ID"].'</td>
                <td>'.$row["Тип инвентаря"].'</td>
                <td>'.$row["Инвентарь"].'</td>
                <td>'.$row["Пол"].'</td>
                <td>'.$row["Цена/час"].'</td>
                <td>'.$row["Доступно (шт)"].'</td>
                <td><input type="checkbox"/></td>
                <td><input type="number" class="quantity" style="width:70px;" min="1" max="'.$row["Доступно (шт)"].'" /></td>
            </tr>';

echo '</tbody>
    </table>
    <hr/>
    <input class="btn btn-primary" style="color:white !important;" type="submit" value="Забронировать выбранный инвентарь" />';

$status = pg_result_status($rezult);
switch ($status)
{
    case PGSQL_COMMAND_OK:
//        echo "allgood.";
        break;
    case PGSQL_BAD_RESPONSE:
//        echo "bad response.";
        break;
    case PGSQL_NONFATAL_ERROR:
//        echo "nonfatal error";
        break;
    case PGSQL_FATAL_ERROR:
//        echo "fatal error";
        break;
    case PGSQL_TUPLES_OK:
//        echo 'records delivered';
        break;
}
pg_free_result($rezult); //not REALLY necessary

  echo '</div>

</div>';
echo
'<script type="text/javascript">
	$("#inventory > a").css("color","rgb(252, 163, 90)");
</script>';
include_once 'skeleton_lastpart.html';
?>