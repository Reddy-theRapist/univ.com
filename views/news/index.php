<div class="row extendedContainer" id="theContainer">
    <div class="col-sm-3">
        <h4 style="text-align:center">Навигация</h4>
        <!-- <nav class="col-sm-3"> -->
        <ul class="nav nav-pills nav-stacked extendedUL">
            <li><button class="btn btn-primary extendedBtn-bottom" onclick="scrollTowards('start')">В начало</button></li>
            <li><button class="btn btn-primary extendedBtn-top" onclick="scrollTowards('end')">В конец</button></li>
        </ul>
        <!-- </nav> -->
        <br>
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
        <?php if (isset($cookies)): ?>
        <p> <?= $cookies ?> </p>
        <?php else:?>
        <p> нету куков</p>
        <?php endif; ?>
        <hr/>
        <?php if (isset($raw_cookies)): ?>
            <p> <?= $raw_cookies ?> </p>
        <?php else:?>
            <p> нету и сырых куков</p>
        <?php endif; ?>




        <div class="newsBlock">
            <h3 >Сегодня: </h3>
            <hr/>
            <ul>
                <li><p>Цены на недвижимость растут</p></li>
                <li><p>Цены на недвижимость падают</p></li>
                <li><p>Дома строятся</p></li>
                <li><p>Офисные здания строятся, всё норм.</p></li>
            </ul>
        </div>
        <div class="newsBlock">
            <h3 >Вчера: </h3>
            <hr/>
            <ul>
                <li><p>Цены на недвижимость растут</p></li>
                <li><p>Цены на недвижимость падают</p></li>
                <li><p>Дома строятся</p></li>
                <li><p>Офисные здания строятся, всё норм.</p></li>
            </ul>
        </div>
        <div class="newsBlock">
            <h3>Позавчера: </h3>
            <hr/>
            <ul>
                <li><p>Цены на недвижимость растут</p></li>
                <li><p>Цены на недвижимость падают</p></li>
                <li><p>Дома строятся</p></li>
                <li><p>Офисные здания строятся, всё норм.</p></li>
            </ul>
        </div>
    </div>

</div>
<script type="text/javascript">
    $("#news > a").css("color","rgb(252, 163, 90)");
</script>