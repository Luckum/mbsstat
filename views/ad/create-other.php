<?php

use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$months = range(1, 12);
?>

<h3 class="page-header">
    Добавить рекламные расходы в категорию <?= $category->name ?>
    <a name="sync_settings_back" href="<?= Url::to(['/ad/index']); ?>" type="button" class="btn btn-primary pull-right">Назад</a>
</h3>
<br />

<?php $form = ActiveForm::begin([
    'id' => 'create-ad-form',
    'options' => ['class' => 'form-horizontal']
]); ?>
    <div class="form-group">
        <label for="name" class="col-sm-2 control-label">Название</label>
        <div class="col-sm-4">
            <input type="text" class="form-control" id="name" name="name">
        </div>
    </div>
    <div class="form-group">
        <label for="price" class="col-sm-2 control-label">Цена</label>
        <div class="col-sm-4  has-feedback">
            <input type="text" class="form-control" id="price" name="price">
            <span class="form-control-feedback">руб.</span>
        </div>
    </div>
    <div class="form-group">
        <label for="paid_date" class="col-sm-2 control-label">Дата оплаты</label>
        <div class="col-sm-2">
            <input type="date" class="form-control" id="paid_date" name="paid_date">
        </div>
    </div>
    <div class="form-group">
        <label for="next_pay_date" class="col-sm-2 control-label">Следующая дата оплаты</label>
        <div class="col-sm-2">
            <input type="date" class="form-control" id="next_pay_date" name="next_pay_date">
        </div>
    </div>
    <div class="form-group">
        <label for="period" class="col-sm-2 control-label">Период оплаты</label>
        <div class="col-sm-2">
            <select id="period" name="period">
                <?php foreach ($months as $month): ?>
                    <option value="<?= $month ?>" <?php if (date('n') == $month): ?> selected <?php endif; ?>>
                        <?= Yii::$app->formatter->asDate(date('Y') . '-' . $month . '-01', 'LLLL'); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-primary" name="create">Добавить</button>
        </div>
    </div>
<?php ActiveForm::end(); ?>