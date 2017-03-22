<p>
    <span class="label label-default"> А ещё можешь подписаться на вот этих уродов: </span>
    <span class="label label-primary"><?= count($twitterUser->advices) ?> </span>
</p>
<hr/>

<?php foreach($twitterUser->advices as $eblan):?>
    <div class="twitter_user">
        <img class="img-responsive img-rounded"
             src="<?= str_replace("_normal.",".",$eblan["profile_image_url"]) ?>" />
        <a href="https://twitter.com/<?=$eblan["screen_name"] ?>" target="_blank"> <span class="label label-default"><?= $eblan["screen_name"]?></span> </a>

        <button type="button" class="btn btn-primary" data-toggle="tooltip" title="дизлайк атписка"
                onclick="subscribe_faggot(<?= $eblan["id_str"]?>,'<?=$eblan["screen_name"]?>')" id="bitch_<?=$eblan["screen_name"]?>">
            падписацца
        </button>

        <blockquote>
            <p><?= $eblan["description"]?></p>
            <footer><?=$eblan["screen_name"]?></footer>
        </blockquote>
    </div>
<?php endforeach; ?>
