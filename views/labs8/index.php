<div class="row extendedContainer" id="theContainer">
    <div class="col-sm-3">
        <h4 style="text-align:center">Навигация</h4>
        <ul class="nav nav-pills nav-stacked extendedUL">
            <li><button class="btn btn-primary extendedBtn-bottom" onclick="scrollTowards('start')">В начало</button></li>
            <li><button class="btn btn-primary extendedBtn-top" onclick="scrollTowards('end')">В конец</button></li>
        </ul>
        <br>
        <div class="input-group extendedUL">

            <?php if (!isset($_SESSION["oauth_token"])&&!isset($_SESSION["oauth_token_secret"])): ?>
            <span class="input-group-btn">
                <a class="btn btn-primary" href="controllers/twitter_auth.php?callback=labs8">Зайти в твиттер <span class="glyphicon glyphicon-retweet"></span> </a>
            </span>

            <?php elseif ($denied):?>
            <p>Ви таки отказались доверять мне.</p>
            <?php endif; ?>

            <?php if ($twitterUser->initialized):?>
                <div>
                    <p>Вы зашли как:</p>
                    <p><strong> <?=$twitterUser->screen_name  ?> </strong></p>
                    <img width="128" height="128" src="<?= str_replace("_normal.",".",$twitterUser->profile_image_url)?>">
                </div>
            <?php endif;?>

        </div>
    </div>

    <div class="col-sm-9">
        <br/>
        <?php if ($twitterUser->initialized):?>
            <?php include_once "Templates/twitter_followers.php"; ?>
            <hr/>
            <?php include_once "Templates/twitter_followed.php"; ?>
            <hr/>
            <?php include_once "Templates/twitter_advices.php"; ?>
        <?php endif;?>
        <?php include "Templates/content_block.php"; ?>
    </div>

</div>

<script type="text/javascript">
    $("#labs > a").css("color","rgb(252, 163, 90)");
</script>