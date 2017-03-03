<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\AdPublic;

$prevAdId = 0;
?>


<h3 class="page-header">Реклама</h3>
<br />

<p><b>ВСЕГО НА РЕКЛАМУ: <?= number_format($adTotal, 2, '.', ' '); ?> руб.</b></p>
<table class="table table-striped table-bordered table-hover">
    <thead>
        <th>Паблики</th>
        <th style="text-align:center">Создатель</th>
        <th style="text-align:center">Цена, руб.</th>
        <th style="text-align:center">Комментарии</th>
    </thead>
    <tbody>
        <?php foreach ($ads as $k => $ad): ?>
            <tr>
                <td><a href="<?= $ad['ad_group']; ?>" target="_blank"><?= $ad['ad_group']; ?></a></td>
                <?php if ($prevAdId != $ad['ad_id']): ?>
                    <?php $rows = AdPublic::getCountById($ad['ad_id']); ?>
                    <td rowspan="<?= $rows; ?>" align="center" style="vertical-align:middle"><a href="<?= $ad['creator']; ?>" target="_blank"><?= $ad['creator']; ?></a></td>
                    <td rowspan="<?= $rows; ?>" align="center" style="vertical-align:middle"><?= number_format($ad['price'], 2, '.', ' '); ?></td>
                    <td rowspan="<?= $rows; ?>" align="center" style="vertical-align:middle"><?= $ad['amount']; ?><?php if ($ad['ad_type'] == 'P'): ?> постов<?php endif; ?></td>
                    <?php $prevAdId = $ad['ad_id']; ?>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>