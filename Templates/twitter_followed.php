<p>
    <span class="label label-default"> Вы подписаны на вот этих ебланы </span>
    <span class="label label-primary"><?= count($twitterUser->followed) ?> </span>
</p>
<hr/>

<?php foreach($twitterUser->followed as $eblan):?>
    <div class="twitter_user">
        <img class="img-responsive img-rounded"
             src="<?= str_replace("_normal.",".",$eblan["profile_image_url"]) ?>" />
        <a href="https://twitter.com/<?=$eblan["screen_name"] ?>" target="_blank"> <span class="label label-default"><?= $eblan["screen_name"]?></span> </a>

        <blockquote>
            <p><?= $eblan["description"]?></p>
            <footer><?=$eblan["screen_name"]?></footer>
        </blockquote>
    </div>
<?php endforeach; ?>
