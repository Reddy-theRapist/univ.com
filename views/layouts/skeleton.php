
<!DOCTYPE html>
<html lang="ru" >
<head>
    <title>the university</title>

    <!-- <script src = "https://www.youtube.com/iframe_api"></script> -->
    <link rel="stylesheet" href="csss/sidebar.css">
    <link rel="stylesheet" href="csss/common.css">
    <link rel="stylesheet" href="csss/mainpage.css">
    <link rel="stylesheet" href="csss/footers.css">
    <link rel="stylesheet" href="csss/main.css">
    <link rel="stylesheet" href="csss/news.css">
    <!--<link rel="stylesheet" href="../csss/services.css">-->
    <!--<link rel="stylesheet" href="../csss/objects.css">-->
    <script src="j-scripts/aScript.js"></script>
    <script src="j-scripts/SubmitOrder.js"></script>
    <link href="images/skating.png" rel="icon" type="image/gif">
    <meta charset="utf-8" />
    <!-- <meta http-equiv="Cache-Control" content="no-cache"> -->
    <!-- <meta http-equiv="Cache-Control" content="private"> -->

    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.0/bootstrap-table.min.css"/>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.0/bootstrap-table.min.js" />

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <script src="https://api-maps.yandex.ru/1.1/index.xml" type="text/javascript"></script>
    <script src="j-scripts/DatabaseProcessingLogic.js"></script>
    <script src="j-scripts/form_handling.js"></script>
    <link rel="stylesheet" href="csss/Normalize.css" />

</head>

<body class="aibody">
<div class="container wrapper">
    <!-- HeADER BLOCK BEGIN -->
    <div class="extendedHeader">
        <img src="images/skiing.png" onclick="hereComesTheBrekotkin()" id="theLogo" />
        <header>
            <h1 style='color:white; font-family:"Helvetica Neue Light", "HelveticaNeue-Light", "Helvetica Neue", Verdana
                                Calibri, Helvetica, Arial, sans-serif'>Suicide Squad's Rent Service</h1>
        </header>
    </div>
    <!-- HEADER BLOCK END  -->
    <!--  -->
    <!-- HORIZONTAL NAVBAR BEGIN  -->
    <nav class="navbar navbar-inverse container" id="start">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbarStuff">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a href="mainpage.php" type="button" class="navbar-brand">SSRS</a>
            </div>

            <div class="container-fluid collapse navbar-collapse" id="navbarStuff">
                <ul class="nav navbar-nav ExtendedNav">
                    <li id="mainpage"><a href="index">Главная</a></li>
                    <li id="news"><a href="news">Новости</a></li>
                    <li id="labs" class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#"> &lt;?php ?&gt;<span class="caret"></span> </a>
                        <ul class="dropdown-menu extendedDropdown">
                            <li><a href="labs8"> LABS(8) </a></li>
<!--                            <li><a href="regexps.php"> 5L </a></li>-->
<!--                            <li><a href="database.php"> 6L </a></li>-->
                        </ul>
                    </li>
                </ul>

                <ul class="nav navbar-nav navbar-right ExtendedNav">
                    <li id="contacts"><a href="contacts">Контакты</a></li>
                    <li id="about" class="activeNBB"><a href="about">О нас</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container" id="HeadingContentDIV">
        <h4 id="HeadingContent"></h4>
    </div>
    <!--HORIZONTAL NAVBAR END  -->
    <!--  -->
    <!-- CONTENT BEGIN  -->
    <div id="loadingDIV"><img src="images/LoaderIcon.gif" ></div>
    <div class="container" id="InnerContentDIV">
        <?php include ($contentPage); ?>
    </div>
</div>
<!-- FOOTER BEGIN -->
<footer class="panel-footer container-fluid extendedFooter" id="end">
    <div id="footerLeftDiv">
        <p>Строим дома такие большие прям ващпе.</p>
        <p> Контакты:<br/>123-456; 234-567; </br> </p>
        <p> Работаем пока солнце высоко.</p>
    </div>
    <div id="footerRightDiv">
        <p>Карта сайта ↓</p>
        <div class="footerDIV">
            <a class="footerLinks" href="index" >Главная</a>
        </div>
        <div class="footerDIV">
            <a class="footerLinks" href="contacts" >Контакты</a>
            <a class="footerLinks" href="about" >О нас</a>
        </div>
    </div>
</footer>
<!-- FOOTER END -->
<iframe style="display:none;" id="hiddenCharacter" width="280" height="160" src="" frameborder="0"></iframe>
</body>

</html>
