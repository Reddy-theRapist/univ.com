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
            <span class="input-group-btn">
                <a class="btn btn-primary" href="twitter_auth">Зайти в твиттер <span class="glyphicon glyphicon-retweet"></span> </a>

			</span>
        </div>
    </div>

    <div class="col-sm-9">
        <?php include "Templates/content_block.php" ?>
    </div>

</div>

<script type="text/javascript">
    $("#labs > a").css("color","rgb(252, 163, 90)");
</script>