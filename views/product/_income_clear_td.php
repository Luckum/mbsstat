<?php
use app\models\ProductDetail;
$sitesCnt = count($sites);
?>
<table style="width: 100%;">
    <?php foreach ($sites as $k => $site): ?>
        <?php $product_details = ProductDetail::getDetailsBySite($site->id, $product['id']); ?>
        <tr>
            <td style="padding: 8px; <?php if (($k + 1) != $sitesCnt): ?>border-bottom: 1px solid #ddd;<?php endif; ?>" class="tooltip-top-td" data-toggle="tooltip" data-placement="top" title="<?= $site->name; ?>">
                <?= number_format(sprintf("%01.2f", $product_details['income_clear']), 2, '.', ' '); ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>