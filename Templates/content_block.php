<?php foreach ($data as $row):?>
<div class="news_contentBlock">
    <div class=".contentHeader"> <p>Захуячено в <?= $row["publication_date"]; ?></p></div>
    <div class="news_innerContent">
        <p><?= $row['content']?></p>
    </div>
    <div class="news_share">
        <button class="btn-info" title="захуячить твит"><span class="glyphicon glyphicon-retweet"></span></button>
    </div>
</div>
<?php endforeach; ?>