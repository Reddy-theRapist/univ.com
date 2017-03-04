<div class="row extendedContainer" id="theContainer">
    <div class="col-sm-3">
        <h4 style="text-align:center;">Навигация</h4>
        <ul class="nav nav-pills nav-stacked extendedUL">
            <li><button class="btn btn-primary extendedBtn-top" onclick="scrollTowards('end')">В конец</button></li>
            <li><button class="btn btn-primary extendedBtn-bottom" onclick="scrollTowards('start')">В начало</button></li>
        </ul>
    </div>

    <div class="col-sm-9">
        <div>
            <h3 style='color:white; font-family: "Helvetica Neue Light", "HelveticaNeue-Light",
						"Helvetica Neue", Calibri, Helvetica, Arial, sans-serif'>
                Suicide Squad's Rent Service - Ваш проводник в экстремельный зимний спорт.</h3><hr/>
        </div>
        <div id="myCarousel" class="carousel slide" data-ride="carousel">
            <!-- Indicators -->
            <ol class="carousel-indicators">
                <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                <li data-target="#myCarousel" data-slide-to="1"></li>
                <li data-target="#myCarousel" data-slide-to="2"></li>
                <li data-target="#myCarousel" data-slide-to="3"></li>
                <li data-target="#myCarousel" data-slide-to="4"></li>
                <li data-target="#myCarousel" data-slide-to="5"></li>
            </ol>

            <!-- Wrapper for slides -->
            <div class="carousel-inner" role="listbox">

                <div class="item active">
                    <img src="images/J_1.jpg" alt="pic 1" width="460" height="345">
                    <div class="carousel-caption">
                        <h3>Лучший инвентарь</h3>
                        <!--<p></p>-->
                    </div>
                </div>

                <div class="item">
                    <img src="images/J_2.jpg" alt="зшс 2" width="460" height="345">
                    <div class="carousel-caption">
                        <h3>Сноуборды из адамантиума</h3>
                        <!--<p>Здесь уже подарили квартиру жерару депардье</p>-->
                    </div>
                </div>

                <div class="item">
                    <img src="images/J_3.jpeg" alt="зшс 3" width="460" height="345">
                    <div class="carousel-caption">
                        <h3>Санки из чугуна</h3>
                    </div>
                </div>

                <div class="item">
                    <img src="images/J_3.jpg" alt="зшс4" width="460" height="345">
                    <div class="carousel-caption">
                        <h3>Горки из моих замерзших слёз</h3>
                    </div>
                </div>
                <div class="item">
                    <img src="images/J_4.jpg" alt="зшс5" width="460" height="345">
                    <div class="carousel-caption">
                        <h3>Одна лыжа на двоих</h3>
                    </div>
                </div>
                <div class="item">
                    <img src="images/J_5.jpg" alt="зшс6" width="460" height="345">
                    <div class="carousel-caption">
                        <h3>Если сломаешь - мы знаем, где ты живёшь!</h3>
                    </div>
                </div>

            </div>

            <!-- Left and right controls -->
            <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
        <blockquote>
            <p>Усталось придумали евреи, чтобы остальные меньше работали. ©</p>
            <footer>Suicide Squad's Rent Service</footer>
        </blockquote>
    </div>

</div>


<?php
//include_once '../htmls/main.html';
echo
'<script type="text/javascript">
	$("#mainpage > a").css("color","rgb(252, 163, 90)");
</script>';
//include_once 'skeleton_lastpart.html';
?>
