<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\AdCategory */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin([
    'id' => 'create-ad-category-form',
    'options' => ['class' => 'form-horizontal']
]); ?>
    <div class="form-group">
        <label for="name" class="col-sm-2 control-label">Название</label>
        <div class="col-sm-4">
            <input type="text" class="form-control" id="name" name="name" value="<?= $model->name ?>">
        </div>
    </div>
    
    <div class="form-group">
        <label for="is_vk" class="col-sm-2 control-label">Реклама во ВКонтакте</label>
        <div class="col-sm-4">
            <input type="checkbox" id="is_vk" name="is_vk" value="1" <?php if ($model->is_vk): ?> checked <?php endif; ?>>
        </div>
    </div>
    
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>
    
<?php ActiveForm::end(); ?>