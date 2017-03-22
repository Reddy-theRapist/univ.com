

<p>
    <span class="label label-default"> На вас подписаны вот эти ебланы </span>
    <span class="label label-primary"><?= count($twitterUser->followers) ?> </span>
</p>
<hr/>

<?php foreach($twitterUser->followers as $eblan):?>
    <div class="twitter_user">
        <img class="img-responsive img-rounded"
             src="<?= str_replace("_normal.",".",$eblan["profile_image_url"]) ?>" />
        <a href="https://twitter.com/<?=$eblan["screen_name"] ?>" target="_blank"> <span class="label label-default"><?= $eblan["screen_name"]?></span> </a>
        <button type="button" class="btn btn-warning" data-toggle="tooltip" title="Забанить у*бка"
                onclick="ban_faggot(<?= $eblan["id_str"] ?>, '<?= $eblan["screen_name"]?>') id="bitch_<?=$eblan["screen_name"]?>">
            /ban
        </button>
        <blockquote>
            <p><?= $eblan["description"]?></p>
            <footer><?=$eblan["screen_name"]?></footer>
        </blockquote>
    </div>
<?php endforeach; ?>
