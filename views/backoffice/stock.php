<?php

use yii\helpers\Html;
use yii\widgets\LinkPager;

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Stock!</h1>
    </div>

    <div class="body-content">

        <a href="#" class="btn btn-lg btn-success">Creat New Best Seller</a>

        <?php foreach ($stock as $product): ?>
        <div class="row">
            <div class="col-lg-4">
                <h2><?= Html::encode("{$product->name} ({$product->epc})") ?>: <?= $product->stock ?> | <a href="#">Edit</a></h2>
            </div>
        </div>
        <?php endforeach; ?>
        <div class="row">
                <?= LinkPager::widget(['pagination' => $pagination]) ?>
        </div>
    </div>

</div>
