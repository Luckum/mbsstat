<?php

use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

?>


<h3 class="page-header">
    Редактировать элемент
    <a name="ad_back" href="<?= Url::to(['/ad/index']); ?>" type="button" class="btn btn-primary pull-right">Назад</a>
    <a name="ad_delete" href="<?= Url::to(['/ad/delete', 'id' => $ad['id']]); ?>" onclick="return confirm('Вы уверены?');" type="button" class="btn btn-danger pull-right" style="margin-right: 15px;">Удалить элемент</a>
</h3>
<br />

<?php $form = ActiveForm::begin([
    'id' => 'create-ad-form',
    'options' => ['class' => 'form-horizontal']
]); ?>
    <div class="form-group">
        <label for="creator" class="col-sm-2 control-label">Автор</label>
        <div class="col-sm-4">
            <input type="text" class="form-control" id="creator" name="creator" value="<?= $ad['creator']; ?>">
        </div>
    </div>
    <?php foreach ($ad_publics as $k => $public): ?>
        <div class="form-group" id="public-container-<?= $public['id']; ?>">
            <label for="creator" class="col-sm-2 control-label"><?php if ($k == 0): ?>Паблики<?php endif; ?></label>
            <div class="col-sm-4">
                <input type="text" class="form-control" id="creator" name="creator" value="<?= $public['ad_group']; ?>" readonly>
            </div>
            <a href="javascript:void(0)" title="Удалить" onclick="deletePublic(<?= $public['id']; ?>);"><i class="fa fa-times" style="margin-top: 10px; color: red;"></i></a>
        </div>
    <?php endforeach; ?>
    <div class="form-group">
        <label for="price" class="col-sm-2 control-label">Цена</label>
        <div class="col-sm-4 has-feedback">
            <input type="text" class="form-control" id="price" name="price" value="<?= $ad['price']; ?>">
            <span class="form-control-feedback">руб.</span>
        </div>
    </div>
    <div class="form-group">
        <label for="amount" class="col-sm-2 control-label">Количество</label>
        <div class="col-sm-2">
            <input type="text" class="form-control" id="amount" name="amount" value="<?= $ad['amount']; ?>">
        </div>
        <div class="col-sm-2">
            <select id="ad_type" name="ad_type" class="form-control">
                <option value="C" <?php if ($ad['ad_type'] == 'C'): ?>selected<?php endif; ?>>Комментариев</option>
                <option value="P" <?php if ($ad['ad_type'] == 'P'): ?>selected<?php endif; ?>>Постов</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-primary" name="update">Сохранить</button>
        </div>
    </div>
<?php ActiveForm::end(); ?>