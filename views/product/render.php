<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

?>


<h3 class="page-header">
    Списать товар
    <a name="render_list" href="<?= Url::to(['/product/renderlist']); ?>" type="button" class="btn btn-primary pull-right">Списанные товары</a>
</h3>
<br />

<?php if (!empty($msg)): ?>
<div class="alert alert-success alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <?= $msg; ?>
</div>

<?php endif; ?>

<?php $form = ActiveForm::begin([
    'id' => 'accept-product-form',
    'options' => ['class' => 'form-horizontal']
]); ?>
    <div class="form-group">
        <label for="category" class="col-sm-2 control-label">Категория</label>
        <div class="col-sm-4">
            <select id="category" name="category" class="form-control" onchange="getProducts(this)">
                <option value="0" disabled selected>- Выберите категорию -</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['id']; ?>"><?= $category['name']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    
    <div class="form-group">
        <label for="product" class="col-sm-2 control-label">Товар</label>
        <div class="col-sm-4">
            <select id="product" name="product" class="form-control" onchange="getRenderProductDetails(this)">
                <option value="0" disabled selected>- Выберите товар -</option>
            </select>
        </div>
    </div>
    
    <div id="render-product-details">
    </div>
<?php ActiveForm::end(); ?>