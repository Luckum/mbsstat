<?php

use yii\helpers\Html;
use yii\helpers\Url;

?>
<h3 class="page-header">
    Добавить категорию
    <a name="sync_settings_back" href="<?= Url::to(['index']); ?>" type="button" class="btn btn-primary pull-right">Назад</a>
</h3>
<br />
<div class="ad-category-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
