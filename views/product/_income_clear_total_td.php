<?php
use app\models\ProductSold;
use app\models\ProductDetail;

$thisMonth = Yii::$app->params['thisMonth'];
$sitesCnt = count($sites);
?>
<table style="width: 100%;">
    <?php foreach ($sites as $k => $site): ?>
        <?php $product_sold = ProductSold::getTotalBySite($product['id'], $site->id, $thisMonth); ?>
        <tr>
            <?php if ($k == 0): ?>
                <?php $product_sold_total = ProductSold::getSumIncomeClear($product['id'], $thisMonth); ?>
                <td rowspan="<?= $sitesCnt; ?>" width="50%" style="padding: 8px; border-right: 1px solid #ddd;">
                    <?= number_format(sprintf("%01.2f", $product_sold_total), 2, '.', ' '); ?>
                </td>
            <?php endif; ?>
            <td style="padding: 8px; <?php if (($k + 1) != $sitesCnt): ?>border-bottom: 1px solid #ddd;<?php endif; ?>" class="tooltip-top-td" data-toggle="tooltip" data-placement="top" title="<?= $site->name; ?>">
                <?= number_format(sprintf("%01.2f", $product_sold['income_clear_total']), 2, '.', ' '); ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>