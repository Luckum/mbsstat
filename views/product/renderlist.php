<?php

use yii\helpers\Html;
use yii\helpers\Url;

?>


<h3 class="page-header">
    Списанные товары
    <a name="render_list_back" href="<?= Url::to(['/product/render']); ?>" type="button" class="btn btn-primary pull-right">Назад</a>
</h3>
<br />

<?php ?>
<table class="table table-striped table-bordered table-hover">
    <thead>
        <th>Дата</th>
        <th>Позиция</th>
        <th>Категория</th>
        <th>Количество, шт.</th>
        <th>Стоимость, руб.</th>
        <th>Итого, руб.</th>
    </thead>
    <tbody>
        <?php foreach ($products as $product): ?>
            <tr>
                <td><?= Yii::$app->formatter->asDate($product->render_date, 'LLLL yyyy'); ?></td>
                <td><?= $product->product->product_name; ?></td>
                <td><?= $product->product->category->name; ?></td>
                <td><?= $product->amount; ?></td>
                <td><?= number_format(sprintf("%01.2f", $product->render_price), 2, '.', ' '); ?></td>
                <td><?= number_format(sprintf("%01.2f", $product->render_price * $product->amount), 2, '.', ' '); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>