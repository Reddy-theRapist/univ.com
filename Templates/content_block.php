<?php foreach ($data as $row):?>
<hr/>
    <div style="margin: 5px;" id="<?= $row['subject'].' '.$row['publication_date']?>">
    <div>
        <p><span class="label label-default">
            Захуячено в <?= DateTime::createFromFormat('Y-m-d G:i:s', $row["publication_date"])->format('d/m/Y G:i:s');?>
             by <?= $row['author'];?>
        </span></p>
        <p> ТЕМА: <b> <span class="label label-primary"> <?= $row['subject'];?> </span></b></p>
    </div>
    <div class="well-lg news_contentBlock">
        <p><?= $row['content']?></p>
    </div>
    <div class="news_share">
        <a href="https://twitter.com/share" title="Захуячить твит" class="twitter-share-button" data-show-count="false">Tweet</a><script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
    </div>
</div>
<?php endforeach; ?>