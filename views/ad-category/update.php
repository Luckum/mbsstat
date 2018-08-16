<?php

use yii\helpers\Html;
use yii\helpers\Url;

?>
<h3 class="page-header">
    Редактировать категорию
    <a href="<?= Url::to(['index']); ?>" type="button" class="btn btn-primary pull-right">Назад</a>
</h3>
<br />
<div class="ad-category-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
