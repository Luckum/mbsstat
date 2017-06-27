<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Product;
use app\models\SyncSetting;
use yii\bootstrap\ActiveForm;
?>
<h3 class="page-header">Настройки синхронизации
<a name="sync_settings_back" href="<?= Url::to(['/sync/index']); ?>" type="button" class="btn btn-primary pull-right">Назад</a></h3>
<br />

<p>Выберите товары для синхронизации склада и <strong>madbearshop.ru</strong></p>
<?php $form = ActiveForm::begin([
    'id' => 'sync-settings-mbs-form',
    'options' => ['class' => 'form-horizontal']
]); ?>
    <div class="sync-select">
        <select id="sync_select_settings_mbs" name="sync_mbs[]" multiple="multiple">
            <?php foreach ($categories as $category): ?>
                <?php $products = Product::getProducts($category['id']); ?>
                <?php foreach ($products as $product): ?>
                    <option value="<?= $product['id']; ?>" data-section="<?= $category['name']; ?>" <?php if (SyncSetting::isInSync($product['id'], 1)): ?> selected="selected" <?php endif; ?>><?= $product['product_name'] ?></option>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-sm-10" style="margin-top: 15px;">
        <button type="submit" class="btn btn-primary" name="sync_mbs_save">Сохранить</button>
    </div>
<?php ActiveForm::end(); ?>

<br /><br /><br />
<p>Выберите товары для синхронизации склада и <strong>sportdrugs.ru</strong></p>
<?php $form = ActiveForm::begin([
    'id' => 'sync-settings-sd-form',
    'options' => ['class' => 'form-horizontal']
]); ?>
    <div class="sync-select">
        <select id="sync_select_settings_sd" name="sync_sd[]" multiple="multiple">
            <?php foreach ($categories as $category): ?>
                <?php $products = Product::getProducts($category['id']); ?>
                <?php foreach ($products as $product): ?>
                    <option value="<?= $product['id']; ?>" data-section="<?= $category['name']; ?>" <?php if (SyncSetting::isInSync($product['id'], 2)): ?> selected="selected" <?php endif; ?>><?= $product['product_name'] ?></option>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-sm-10" style="margin-top: 15px;">
        <button type="submit" class="btn btn-primary" name="sync_sd_save">Сохранить</button>
    </div>
<?php ActiveForm::end(); ?>