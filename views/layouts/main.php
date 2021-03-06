<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
$this->title = Yii::$app->params['name'];
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->params['name'],
        'brandUrl' => '/stat/report',
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            ['label' => 'Отчет', 'url' => ['/stat/report']],
            ['label' => 'ТОП ПРОДАЖ', 'url' => ['/stat/top']],
            ['label' => 'Реклама', 'url' => ['/ad/index'], 'active' => Yii::$app->controller->id == 'ad', 'visible' => !Yii::$app->user->isGuest && Yii::$app->user->identity->group == 'admin'],
            ['label' => 'Принять товар', 'url' => ['/product/accept']],
            ['label' => 'Списать товар', 'url' => ['/product/render'], 'active' => Yii::$app->controller->action->id == 'render' || Yii::$app->controller->action->id == 'renderlist', 'visible' => !Yii::$app->user->isGuest && Yii::$app->user->identity->group == 'admin'],
            ['label' => 'Синхронизация', 'url' => ['/sync/index'], 'active' => Yii::$app->controller->id == 'sync', 'visible' => !Yii::$app->user->isGuest && Yii::$app->user->identity->group == 'admin'],
            Yii::$app->user->isGuest ? (
                ['label' => 'Login', 'url' => ['/site/login']]
            ) : (
                '<li>'
                . Html::beginForm(['/logout'], 'post')
                . Html::submitButton(
                    'Выйти (' . Yii::$app->user->identity->login . ')',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>'
            )
        ],
    ]);
    NavBar::end();
    ?>

    <div class="container container-main">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
    </div>
</div>


<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
