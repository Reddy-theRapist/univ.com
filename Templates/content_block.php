<?php foreach ($data as $row):?>
<div class="news_contentBlock">
    <div class=".contentHeader">
        <p>Захуячено в
            <?php
            $date = DateTime::createFromFormat('Y-m-d G:i:s', $row["publication_date"]);
            $date=$date->format('d/m/Y G:i:s');
            echo $date;?></p>
    </div>
    <div class="news_innerContent">
        <p><?= $row['content']?></p>
    </div>
    <div class="news_share">
        <button class="btn-info" title="захуячить твит"><span style="width: 30px; height: 40px;" class="glyphicon glyphicon-retweet"></span></button>
    </div>
</div>
<?php endforeach; ?>