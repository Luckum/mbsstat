<?php

use yii\helpers\Html;
use yii\helpers\Url;

use app\models\ProductSold;

$prevCat = '';
Yii::$app->formatter->locale = 'ru-RU';
?>

<h3 class="page-header">
    ТОП ПРОДАЖ
    <div class="btn-group pull-right">
        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"><?= Yii::$app->formatter->asDate($thisMonth, 'LLLL yyyy'); ?>&nbsp;&nbsp;&nbsp;<span class="caret"></span></button>
        <ul class="dropdown-menu" role="menu">
            <?php foreach ($statMonthes as $sMonth): ?>
                <?php if ($sMonth['sale_date'] != $thisMonth): ?>
                    <li><a href="<?= Url::to(['/stat/top', 'd' => $sMonth['sale_date']]); ?>"><?= Yii::$app->formatter->asDate($sMonth['sale_date'], 'LLLL yyyy'); ?></a></li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    </div>
</h3>
<br />

<div class="wrapper" style="display: flex;">
    <nav id="sidebar">
        <ul class="list-unstyled components">
            <?php foreach ($tops as $top): ?>
                <?php if ($top['name'] != $prevCat): ?>
                    <li><a href="#td-<?= $top['id']; ?>"><?= $top['name']; ?></a></li>
                    <?php $prevCat = $top['name']; ?>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    </nav>
    <?php $prevCat = ''; ?>
    <div id="content">
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <th>Позиция</th>
                <th>Продано, шт.</th>
                <th>Выручка, руб.</th>
            </thead>
            <tbody>
                <?php foreach ($tops as $top): ?>
                    <?php if ($top['name'] != $prevCat): ?>
                        <tr>
                            <td colspan="3" class="td-title" id="td-<?= $top['id']; ?>"><?= $top['name']; ?></td>
                        </tr>
                        <?php $prevCat = $top['name']; ?>
                    <?php endif; ?>
                    <?php $product_sold_total = ProductSold::getSoldTotalSell($top['product_id'], $thisMonth); ?>
                    <tr>
                        <td><?= $top['product_name']; ?></td>
                        <td><?= $top['total']; ?></td>
                        <td><?= number_format(sprintf("%01.2f", $product_sold_total), 2, '.', ' '); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div id="back-top">
        <a href="javascript:void(0);" id="up-btn">ВВЕРХ</a>  
    </div>
</div>