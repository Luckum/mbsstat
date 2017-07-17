<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Product;
use app\models\ProductSold;
use app\models\Income;
use yii\bootstrap\Modal;
use kartik\editable\Editable;
use app\models\ProductDetail;

$sitesCnt = count($sites);
?>


<h3 class="page-header">
    Отчет
    <div class="btn-group pull-right">
        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"><?= Yii::$app->formatter->asDate($thisMonth, 'LLLL yyyy'); ?>&nbsp;&nbsp;&nbsp;<span class="caret"></span></button>
        <ul class="dropdown-menu" role="menu">
            <?php foreach ($statMonthes as $sMonth): ?>
                <?php if ($sMonth['sale_date'] != $thisMonth): ?>
                    <li><a href="<?= Url::to(['/stat/report', 'd' => $sMonth['sale_date']]); ?>"><?= Yii::$app->formatter->asDate($sMonth['sale_date'], 'LLLL yyyy'); ?></a></li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    </div>
</h3>
<br />


<ul class="nav nav-tabs">
    <?php foreach ($categories as $k => $category): ?>
        <li <?php if ($k == 0): ?>class="active"<?php endif; ?>><a href="#<?= $category['id']; ?>" data-toggle="tab"><?= $category['name']; ?></a></li>
    <?php endforeach; ?>
        <li><a href="#summary" data-toggle="tab"><b>СВОДНАЯ</b></a></li>
</ul>

<div class="tab-content">
    <?php foreach ($categories as $k => $category): ?>
        <?php $products = Product::getProducts($category['id']); ?>
        <div class="tab-pane <?php if ($k == 0): ?>active<?php endif; ?>" id="<?= $category['id']; ?>">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <th>Позиция</th>
                    <th>Чистая, руб.</th>
                    <th>Закупка, руб.</th>
                    <th>Продажа, руб.</th>
                    <th>Поставлено, шт.</th>
                    <th>Продано, шт.</th>
                    <th>В наличии, шт.</th>
                    <th>Общая чистая, руб.</th>
                    <th>Выручка, руб.</th>
                    <th>Комментарий</th>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?= $product['product_name']; ?></td>
                            <td style="padding: 0" id="td_income_clear_<?= $product['id']; ?>">
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
                            </td>
                            <td style="cursor: pointer; vertical-align: middle;" onclick="changePricePurchase(this);" id="td_price_purchase_<?= $product['id']; ?>">
                                <span><?= number_format($product['price_purchase'], 2, '.', ' '); ?></span>
                                <input type="hidden" name="product_id_td" value="<?= $product['id']; ?>">
                                <input type="hidden" name="product_name_td" value="<?= $product['product_name']; ?>">
                            </td>
                            <td style="padding: 0">
                                <table style="width: 100%;">
                                    <?php foreach ($sites as $k => $site): ?>
                                        <?php $product_details = ProductDetail::getDetailsBySite($site->id, $product['id']); ?>
                                        <tr>
                                            <td onclick="changePriceSelling(this);" id="td_price_selling_<?= $product['id']; ?>_<?= $site->id; ?>" style="cursor: pointer; padding: 8px; <?php if (($k + 1) != $sitesCnt): ?>border-bottom: 1px solid #ddd;<?php endif; ?>" class="tooltip-top-td" data-toggle="tooltip" data-placement="top" title="<?= $site->name; ?>">
                                                <span><?= number_format($product_details['price_selling'], 2, '.', ' '); ?></span>
                                                <input type="hidden" name="product_id_td" value="<?= $product['id']; ?>">
                                                <input type="hidden" name="site_id_td" value="<?= $site->id; ?>">
                                                <input type="hidden" name="site_name_td" value="<?= $site->name; ?>">
                                                <input type="hidden" name="product_name_td" value="<?= $product['product_name']; ?>">
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </table>
                            </td>
                            <td style="vertical-align: middle;"><?= $product['amount_supplied']; ?></td>
                            <td style="padding: 0;">
                                <table style="width: 100%;">
                                    <?php foreach ($sites as $k => $site): ?>
                                        <?php $product_sold = ProductSold::getTotalBySite($product['id'], $site->id, $thisMonth); ?>
                                        <tr>
                                            <?php if ($k == 0): ?>
                                                <?php $product_sold_total = ProductSold::getSumByProduct($product['id'], $thisMonth); ?>
                                                <td rowspan="<?= $sitesCnt; ?>" width="50%" style="padding: 8px; border-right: 1px solid #ddd;">
                                                    <?= !empty($product_sold_total) ? $product_sold_total : 0 ?>
                                                </td>
                                            <?php endif; ?>
                                            <td style="padding: 8px; <?php if (($k + 1) != $sitesCnt): ?>border-bottom: 1px solid #ddd;<?php endif; ?>" class="tooltip-top-td" data-toggle="tooltip" data-placement="top" title="<?= $site->name; ?>">
                                                <?= isset($product_sold['amount']) ? $product_sold['amount'] : '0'; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </table>
                            </td>
                            <td style="vertical-align: middle;"><?= $product['amount_in_stock']; ?></td>
                            <td style="padding: 0;" id="td_income_clear_total_<?= $product['id']; ?>">
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
                            </td>
                            <td id="td_income_<?= $product['id']; ?>" style="padding: 0;">
                                <table style="width: 100%;">
                                    <?php foreach ($sites as $k => $site): ?>
                                        <?php $product_sold = ProductSold::getTotalBySite($product['id'], $site->id, $thisMonth); ?>
                                        <tr>
                                            <?php if ($k == 0): ?>
                                                <?php $product_sold_total = ProductSold::getSumIncome($product['id'], $thisMonth); ?>
                                                <td rowspan="<?= $sitesCnt; ?>" width="50%" style="padding: 8px; border-right: 1px solid #ddd;">
                                                    <?= number_format(sprintf("%01.2f", $product_sold_total), 2, '.', ' '); ?>
                                                </td>
                                            <?php endif; ?>
                                            <td style="padding: 8px; <?php if (($k + 1) != $sitesCnt): ?>border-bottom: 1px solid #ddd;<?php endif; ?>" class="tooltip-top-td" data-toggle="tooltip" data-placement="top" title="<?= $site->name; ?>">
                                                <?= number_format(sprintf("%01.2f", $product_sold['income_total']), 2, '.', ' '); ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </table>
                            </td>
                            <td style="cursor: pointer;" onclick="changeComment(this);" id="td_comment_<?= $product['id']; ?>">
                                <span><?= $product['comment']; ?></span>
                                <input type="hidden" name="product_id_td" value="<?= $product['id']; ?>">
                                <input type="hidden" name="product_name_td" value="<?= $product['product_name']; ?>">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endforeach; ?>
        <div class="tab-pane" id="summary">
            <br />
            <?php if (!Yii::$app->user->isGuest && Yii::$app->user->identity->group == 'admin'): ?>
                <?php Modal::begin([
                    'id' => 'outlay_modal',
                    'header' => '<h4>' . 'Добавить затраты' . '</h4>',
                    'toggleButton' => ['label' => 'Добавить затраты', 'class' => 'btn btn-success'],
                    'footer' => '<a class="btn btn-default" data-dismiss="modal" aria-hidden="true">' . 'Закрыть' . '</a>
                                 <button id="add-outlay" class="btn btn-success" type="submit" onclick="addOutlay()" data-dismiss="modal">' . 'Добавить' . '</button>',
                ]); ?>
                    
                    <form action="" method="post" id="outlay-add-form">
                        <div class="form-group">
                            <label for="outlay-type" class="control-label">Тип затрат</label>
                            <input type="text" class="form-control" id="outlay-type" name="type">
                            <label for="outlay-amount" class="control-label">Сумма, руб.</label>
                            <input type="text" class="form-control" id="outlay-amount" name="amount">
                        </div>
                    </form>
                
                <?php Modal::end(); ?>
                
                <?php Modal::begin([
                    'id' => 'outlay_modal_edit',
                    'header' => '<h4>' . 'Редактировать затраты' . '</h4>',
                    'footer' => '<a class="btn btn-default" data-dismiss="modal" aria-hidden="true">' . 'Закрыть' . '</a>
                                 <button id="add-outlay" class="btn btn-success" type="submit" onclick="updateOutlay()" data-dismiss="modal">' . 'Сохранить' . '</button>',
                ]); ?>
                    
                    <form action="" method="post" id="outlay-add-form">
                        <div class="form-group">
                            <label for="outlay-type-edit" class="control-label">Тип затрат</label>
                            <input type="text" class="form-control" id="outlay-type-edit" name="type">
                            <label for="outlay-amount-edit" class="control-label">Сумма, руб.</label>
                            <input type="text" class="form-control" id="outlay-amount-edit" name="amount">
                            <input type="hidden" id="outlay-id-edit" value="">
                        </div>
                    </form>
                
                <?php Modal::end(); ?>
                
                <?php Modal::begin([
                    'id' => 'pickup_modal',
                    'header' => '<h4>' . 'Забрать' . '</h4>',
                    'toggleButton' => ['label' => 'Забрать', 'class' => 'btn btn-success'],
                    'footer' => '<a class="btn btn-default" data-dismiss="modal" aria-hidden="true">' . 'Закрыть' . '</a>
                                 <button id="pickup" class="btn btn-success" type="submit" onclick="pickup()" data-dismiss="modal">' . 'Забрать' . '</button>',
                ]); ?>
                    
                    <form action="" method="post" id="pickup-form">
                        <div class="form-group">
                            <label for="pickup-amount" class="control-label">Сумма, руб.</label>
                            <input type="text" class="form-control" id="pickup-amount" name="amount">
                        </div>
                    </form>
                
                <?php Modal::end(); ?>
                <br /><br />
            <?php endif; ?>
            
            <div id="summary_t">
                <div id="revenue-tbl">
                    <table class="table table-striped table-bordered table-hover">
                        <?php foreach ($incomes as $income): ?>
                            <tr>
                                <td><?= $income->type; ?></td>
                                <td><?= number_format($income->amount, 2, '.', ' '); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                
                <p><b>ЗАТРАТЫ</b></p>
                
                <table class="table table-striped table-bordered table-hover">
                    <?php foreach ($outlays as $outlay): ?>
                        <tr>
                            <td><span class="o-type"><?= $outlay->type; ?></span></td>
                            <td><span class="o-amount"><?= number_format($outlay->amount, 2, '.', ' '); ?></span></td>
                            <?php if (!Yii::$app->user->isGuest && Yii::$app->user->identity->group == 'admin'): ?>
                                <td>
                                    <a href="javascript:void(0);" title="Редактировать" data-toggle="modal" data-target="#outlay_modal_edit" onclick="showOutlayModal(this);"><span class="glyphicon glyphicon-pencil"></span></a>
                                    <a href="javascript:void(0)" title="Удалить" onclick="deleteOutlay(this);"><span class="glyphicon glyphicon-trash"></span></a>
                                    <input type="hidden" class="o-id" value="<?= $outlay->id; ?>">
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </table>
                
                <p><b>ЗАБРАНО</b></p>
                
                <table class="table table-striped table-bordered table-hover">
                    <?php foreach ($pickups as $pickup): ?>
                        <tr>
                            <td><?= $pickup->pickup_datetime; ?></td>
                            <td><?= number_format($pickup->amount, 2, '.', ' '); ?> руб.</td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td><b>ВСЕГО ЗАБРАНО:</b></td>
                        <td><b><?= number_format($totalPickup, 2, '.', ' '); ?> руб.</b></td>
                    </tr>
                </table>
                <p id="cashbox-container"><b>В КАССЕ: <?= number_format($cashbox, 2, '.', ' '); ?> руб.</b></p>
                
                <p><b>ОСТАТОК</b></p>
                <table class="table table-striped table-bordered table-hover">
                    <tr>
                        <td>ЗАКУПОЧНАЯ</td>
                        <td id="residue-purchase-td"><?= number_format($residuePurchase, 2, '.', ' '); ?> руб.</td>
                    </tr>
                    <tr>
                        <td>ДОЛГОВАЯ</td>
                        <td><?= number_format($residueDebt, 2, '.', ' '); ?> руб.</td>
                    </tr>
                    <tr>
                        <td><b>ОБЩАЯ</b></td>
                        <td id="residue-total-td"><b><?= number_format($residuePurchase + $residueDebt + $cashbox, 2, '.', ' '); ?> руб.</b></td>
                    </tr>
                </table>
            </div>
        </div>
</div>

<?php Modal::begin([
    'id' => 'price_selling_modal',
    'header' => '<h4>' . 'Изменить цену' . '</h4>',
    'footer' => '<a class="btn btn-default" data-dismiss="modal" aria-hidden="true">' . 'Закрыть' . '</a>
                 <button id="update-price_selling" class="btn btn-success" type="submit" onclick="updatePriceSelling()" data-dismiss="modal">' . 'Сохранить' . '</button>',
]); ?>
    
    <form action="" method="post" id="price-selling-update-form">
        <div class="form-group">
            <p><label id="product_name_label_s" class="control-label"></label></p>
            <p><label id="site_name_label" class="control-label"></label></p>
            <label for="price_selling_update" class="control-label">Продажная цена, руб.</label>
            <input type="text" class="form-control" id="price_selling_update" name="price_selling">
            <input type="hidden" id="product_id_update" value="">
            <input type="hidden" id="site_id_update" value="">
        </div>
    </form>

<?php Modal::end(); ?>

<?php Modal::begin([
    'id' => 'comment_modal',
    'header' => '<h4>' . 'Изменить комментарий' . '</h4>',
    'footer' => '<a class="btn btn-default" data-dismiss="modal" aria-hidden="true">' . 'Закрыть' . '</a>
                 <button id="update-comment" class="btn btn-success" type="submit" onclick="updateComment()" data-dismiss="modal">' . 'Сохранить' . '</button>',
]); ?>
    
    <form action="" method="post" id="comment-update-form">
        <div class="form-group">
            <p><label id="product_name_label_c" class="control-label"></label></p>
            <label for="comment_update" class="control-label">Комментарий</label>
            <textarea class="form-control" id="comment_update" name="comment" rows="5"></textarea>
            <input type="hidden" id="product_id_update_c" value="">
        </div>
    </form>

<?php Modal::end(); ?>

<?php Modal::begin([
    'id' => 'price_purchase_modal',
    'header' => '<h4>' . 'Изменить цену закупки' . '</h4>',
    'footer' => '<a class="btn btn-default" data-dismiss="modal" aria-hidden="true">' . 'Закрыть' . '</a>
                 <button id="update-price_purchase" class="btn btn-success" type="submit" onclick="updatePricePurchase()" data-dismiss="modal">' . 'Сохранить' . '</button>',
]); ?>
    
    <form action="" method="post" id="price-purchase-update-form">
        <div class="form-group">
            <p><label id="product_name_label" class="control-label"></label></p>
            <label for="price_purchase_update" class="control-label">Закупочная цена, руб.</label>
            <input type="text" class="form-control" id="price_purchase_update" name="price_purchase">
            <input type="hidden" id="product_id_update" value="">
        </div>
    </form>

<?php Modal::end(); ?>