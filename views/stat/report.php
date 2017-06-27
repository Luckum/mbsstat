<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Product;
use app\models\ProductSold;
use app\models\Income;
use yii\bootstrap\Modal;
use kartik\editable\Editable;

$thisMonth = date("Y-m-01");
?>


<h3 class="page-header">Отчет</h3>
<br />


<ul class="nav nav-tabs">
    <?php foreach ($categories as $k => $category): ?>
        <li <?php if ($k == 0): ?>class="active"<?php endif; ?>><a href="#<?= $category['id']; ?>" data-toggle="tab"><?= $category['name']; ?></a></li>
    <?php endforeach; ?>
        <li><a href="#summary" data-toggle="tab"><b>СВОДНАЯ</b></a></li>
</ul>

<div class="tab-content">
    <?php foreach ($categories as $k => $category): ?>
        <?php $products = Product::getProductsByCategory($category['id']); ?>
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
                        <?php $amount_sold = ProductSold::getTotalByProduct($product['id'], $thisMonth);?>
                        <?php $amount_sold_amount = isset($amount_sold[0]['amount']) ? $amount_sold[0]['amount'] : 0; ?>
                        <tr>
                            <td><?= $product['product_name']; ?></td>
                            <td><?= number_format(sprintf("%01.2f", $product['price_selling'] - $product['price_purchase']), 2, '.', ' '); ?></td>
                            <td style="cursor: pointer;" onclick="changePricePurchase(this);" id="td_price_purchase_<?= $product['id']; ?>">
                                <span><?= number_format($product['price_purchase'], 2, '.', ' '); ?></span>
                                <input type="hidden" name="product_id_td" value="<?= $product['id']; ?>">
                            </td>
                            <td style="cursor: pointer;" onclick="changePriceSelling(this);" id="td_price_selling_<?= $product['id']; ?>">
                                <span><?= number_format($product['price_selling'], 2, '.', ' '); ?></span>
                                <input type="hidden" name="product_id_td" value="<?= $product['id']; ?>">
                                <input type="hidden" name="site_id_td" value="<?= $product['site_id']; ?>">
                                <input type="hidden" name="site_name_td" value="<?= $product['name']; ?>">
                            </td>
                            <td><?= $product['amount_supplied']; ?></td>
                            <td style="padding: 0 0 0 8px;">
                                <table style="width: 100%;">
                                    
                                    <tr>
                                        <td rowspan="3" width="50%"><?= $amount_sold_amount; ?></td>
                                        <td>1</td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                    </tr>
                                </table>
                            </td>
                            <td><?= $product['amount_supplied'] - $amount_sold_amount; ?></td>
                            <td><?= number_format(sprintf("%01.2f", ($product['price_selling'] - $product['price_purchase']) * $amount_sold_amount), 2, '.', ' '); ?></td>
                            <td id="td_income_<?= $product['id']; ?>"><?= number_format(sprintf("%01.2f", $product['price_selling'] * $amount_sold_amount), 2, '.', ' '); ?></td>
                            <td style="cursor: pointer;" onclick="changeComment(this);" id="td_comment_<?= $product['id']; ?>">
                                <span><?= $product['comment']; ?></span>
                                <input type="hidden" name="product_id_td" value="<?= $product['id']; ?>">
                                <input type="hidden" name="site_id_td" value="<?= $product['site_id']; ?>">
                                <input type="hidden" name="site_name_td" value="<?= $product['name']; ?>">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endforeach; ?>
        <div class="tab-pane" id="summary">
            <br />
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
            
            <div id="summary_t">
                <table class="table table-striped table-bordered table-hover">
                    <?php foreach ($incomes as $income): ?>
                        <tr>
                            <td><?= $income->type; ?></td>
                            <td><?= number_format($income->amount, 2, '.', ' '); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                
                <p><b>ЗАТРАТЫ</b></p>
                
                <table class="table table-striped table-bordered table-hover">
                    <?php foreach ($outlays as $outlay): ?>
                        <tr>
                            <td><span class="o-type"><?= $outlay->type; ?></span></td>
                            <td><span class="o-amount"><?= number_format($outlay->amount, 2, '.', ' '); ?></span></td>
                            <td>
                                <a href="javascript:void(0);" title="Редактировать" data-toggle="modal" data-target="#outlay_modal_edit" onclick="showOutlayModal(this);"><span class="glyphicon glyphicon-pencil"></span></a>
                                <a href="javascript:void(0)" title="Удалить" onclick="deleteOutlay(this);"><span class="glyphicon glyphicon-trash"></span></a>
                                <input type="hidden" class="o-id" value="<?= $outlay->id; ?>">
                            </td>
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
                <p><b>В КАССЕ: <?= number_format($cashbox, 2, '.', ' '); ?> руб.</b></p>
                
                <p><b>ОСТАТОК</b></p>
                <table class="table table-striped table-bordered table-hover">
                    <tr>
                        <td>ЗАКУПОЧНАЯ</td>
                        <td><?= number_format($residuePurchase, 2, '.', ' '); ?> руб.</td>
                    </tr>
                    <tr>
                        <td>ДОЛГОВАЯ</td>
                        <td><?= number_format($residueDebt, 2, '.', ' '); ?> руб.</td>
                    </tr>
                    <tr>
                        <td><b>ОБЩАЯ</b></td>
                        <td><b><?= number_format($residuePurchase + $residueDebt + $cashbox, 2, '.', ' '); ?> руб.</b></td>
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
            <p><label id="site_name_label_c" class="control-label"></label></p>
            <label for="comment_update" class="control-label">Комментарий</label>
            <textarea class="form-control" id="comment_update" name="comment" rows="5"></textarea>
            <input type="hidden" id="product_id_update_c" value="">
            <input type="hidden" id="site_id_update_c" value="">
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
            <label for="price_purchase_update" class="control-label">Закупочная цена, руб.</label>
            <input type="text" class="form-control" id="price_purchase_update" name="price_purchase">
            <input type="hidden" id="product_id_update" value="">
        </div>
    </form>

<?php Modal::end(); ?>