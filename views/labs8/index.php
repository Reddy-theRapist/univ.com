<div class="row extendedContainer" id="theContainer">
    <div class="col-sm-3">
        <h4 style="text-align:center">Навигация</h4>
        <ul class="nav nav-pills nav-stacked extendedUL">
            <li><button class="btn btn-primary extendedBtn-bottom" onclick="scrollTowards('start')">В начало</button></li>
            <li><button class="btn btn-primary extendedBtn-top" onclick="scrollTowards('end')">В конец</button></li>
        </ul>
        <br>
        <div class="input-group extendedUL">


            <?php if ($denied===true) die($_GET["denied"]); ?>

            <?php if (!isset($_SESSION["denied"])&&!isset($_SESSION["oauth_token"])&&!isset($_SESSION["oauth_token_secret"])): ?>
            <span class="input-group-btn">
                <a class="btn btn-primary" href="controllers/twitter_auth.php?callback=labs8">Зайти в твиттер <span class="glyphicon glyphicon-retweet"></span> </a>
            </span>
            <?php elseif (isset($_GET["denied"])):?>
            <p>Ви таки отказались доверять мне.</p>
            <?php elseif($_SESSION["oauth_token"]&&$_SESSION["oauth_token_secret"]):?>
                <div>
                    <p>Вы зашли как <strong> <?php echo 'хуй знает кто выйди блять и зайди обратно';?></strong></p>
                </div>
            <?php endif;?>



        </div>
    </div>

    <div class="col-sm-9">

        <?php if (isset($_SESSION['oauth_token'])): ?>
        <p>всё ок, сейчас есть oauth_token: <?= $_SESSION['oauth_token'] ?></p>
        <hr/>
        <?php else:?>
        <p>нет oauth_token</p>
        <?php endif; ?>

        <?php if (isset($twitter_user)):?>
                <div>
                    <p>всё ок, пользователь есть.</p>
                    <hr/>
                </div>
                <?php endif;?>

        <?php include "Templates/content_block.php" ?>


    </div>

</div>

<script type="text/javascript">
    $("#labs > a").css("color","rgb(252, 163, 90)");
</script>