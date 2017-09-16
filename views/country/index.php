<?php

use yii\helpers\Html;
use yii\widgets\LinkPager;

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Countries!</h1>
    </div>

    <div class="body-content">

        <?php foreach ($countries as $country): ?>
        <div class="row">
            <div class="col-lg-4">
                <h2><?= Html::encode("{$country->name} ({$country->code})") ?>: <?= $country->population ?></h2>
            </div>
        </div>
        <?php endforeach; ?>
        <div class="row">
                <?= LinkPager::widget(['pagination' => $pagination]) ?>
        </div>
    </div>

</div>
