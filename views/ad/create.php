<?php

use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

?>


<h3 class="page-header">
    Добавить ссылку
    <a name="sync_settings_back" href="<?= Url::to(['/ad/index']); ?>" type="button" class="btn btn-primary pull-right">Назад</a>
</h3>
<br />

<?php $form = ActiveForm::begin([
    'id' => 'create-ad-form',
    'options' => ['class' => 'form-horizontal']
]); ?>
    <div class="form-group">
        <label for="public" class="col-sm-2 control-label">Паблик</label>
        <div class="col-sm-4">
            <input type="text" class="form-control" id="public" name="public">
        </div>
    </div>
    <div class="form-group">
        <label for="creator" class="col-sm-2 control-label">Автор</label>
        <div class="col-sm-4">
            <select id="creator" name="creator" class="form-control" onchange="getAdPrice(this)">
                <option value="0" disabled selected>- Выберите автора -</option>
                <option value="-1">Добавить...</option>
                <?php foreach ($creators as $creator): ?>
                    <option value="<?= $creator['id']; ?>"><?= $creator['creator']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="form-group" id="new-creator-container" style="display: none;">
        <label for="new_creator" class="col-sm-2 control-label">Добавить автора</label>
        <div class="col-sm-4">
            <input type="text" class="form-control" id="new_creator" name="new_creator">
        </div>
    </div>
    <div class="form-group">
        <label for="amount" class="col-sm-2 control-label">Количество</label>
        <div class="col-sm-2">
            <input type="text" class="form-control" id="amount" name="amount">
        </div>
        <div class="col-sm-2">
            <select id="ad_type" name="ad_type" class="form-control">
                <option value="C">Комментариев</option>
                <option value="P">Постов</option>
            </select>
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
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-primary" name="create">Добавить</button>
        </div>
    </div>
<?php ActiveForm::end(); ?>