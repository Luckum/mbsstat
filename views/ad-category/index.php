<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

?>
<h3 class="page-header">
    Рекламные категории
    <a href="<?= Url::to(['/ad/index']); ?>" type="button" class="btn btn-primary pull-right">Назад</a>
    <a href="<?= Url::to(['create']); ?>" type="button" class="btn btn-success pull-right" style="margin-right: 20px;">Добавить</a>
</h3>
<br />

<div class="ad-category-index col-md-offset-2 col-md-8">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}'
            ],
        ],
    ]); ?>
</div>
